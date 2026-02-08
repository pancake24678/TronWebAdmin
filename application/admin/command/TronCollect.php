<?php
namespace app\admin\command;

use app\common\library\TronService;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;

class TronCollect extends Command
{
    protected function configure()
    {
        $this->setName('tron:collect')
            ->addOption('address', 'a', Option::VALUE_REQUIRED, 'user address', '')
            ->addOption('amount', 'm', Option::VALUE_REQUIRED, 'amount in USDT', '')
            ->setDescription('Collect USDT from user address');
    }

    protected function execute(Input $input, Output $output)
    {
        $address = trim((string)$input->getOption('address'));
        $amount = trim((string)$input->getOption('amount'));
        if ($address === '' || $amount === '') {
            $output->writeln('address and amount are required');
            return;
        }
        $amountSun = (int)round(((float)$amount) * 1000000);
        $tron = new TronService();
        $resp = $tron->collect($address, $amountSun);
        $output->writeln(json_encode($resp, JSON_UNESCAPED_UNICODE));
    }
}
