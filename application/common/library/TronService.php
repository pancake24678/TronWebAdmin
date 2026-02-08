<?php
namespace app\common\library;

use IEXBase\TronAPI\Tron;
use IEXBase\TronAPI\Provider\HttpProvider;
use IEXBase\TronAPI\Exception\TronException;
use think\Config;

class TronService
{
    protected $tron;
    protected $config;

    public function __construct()
    {
        if (!class_exists('IEXBase\\TronAPI\\Tron')) {
            throw new \RuntimeException('iexbase/tron-api 未安装');
        }
        $this->config = Config::get('tron');
        $fullNode = new HttpProvider($this->config['full_node']);
        $solidityNode = new HttpProvider($this->config['solidity_node']);
        $eventServer = new HttpProvider($this->config['event_server']);
        $this->tron = new Tron($fullNode, $solidityNode, $eventServer);
        if (!empty($this->config['admin_private_key'])) {
            $this->tron->setPrivateKey($this->config['admin_private_key']);
        }
        if (!empty($this->config['admin_address'])) {
            $this->tron->setAddress($this->config['admin_address']);
        }
    }

    public function contract(string $address)
    {
        return $this->tron->contract($address);
    }

    public function usdtBalanceOf(string $address): int
    {
        $c = $this->contract($this->config['usdt_contract']);
        $balanceRaw = $c->balanceOf($address, false);
        return (int)$balanceRaw;
    }

    public function trxBalanceOf(string $address): int
    {
        return (int)$this->tron->getBalance($address);
    }

    public function collect(string $userAddress, int $amount): array
    {
        $contractAddress = $this->config['collector_contract'];
        $contract = $this->contract($contractAddress);
        $resp = $contract->collect($userAddress, $amount)->send([
            'feeLimit' => $this->config['fee_limit']
        ]);
        return is_array($resp) ? $resp : [];
    }

    public function transferUsdtFrom(string $from, string $to, int $amount): array
    {
        $usdt = $this->config['usdt_contract'];
        $ref = new \ReflectionClass(\IEXBase\TronAPI\TRC20Contract::class);
        $abiPath = dirname($ref->getFileName()) . DIRECTORY_SEPARATOR . 'trc20.json';
        $abi = json_decode(file_get_contents($abiPath), true);
        $contractHex = $this->tron->address2HexString($usdt);
        $fromHex = $this->tron->address2HexString($from);
        $toHex = $this->tron->address2HexString($to);
        $ownerHex = $this->tron->address['hex'];
        $allow = $this->tron->getTransactionBuilder()->triggerConstantContract(
            $abi,
            $contractHex,
            'allowance',
            [$fromHex, $ownerHex],
            '410000000000000000000000000000000000000000'
        );
        $allowStr = is_array($allow) && isset($allow[0]) && method_exists($allow[0], 'toString') ? $allow[0]->toString() : '0';
        if (bccomp($allowStr, (string)$amount, 0) < 0) {
            throw new TronException('Insufficient allowance');
        }
        $tx = $this->tron->getTransactionBuilder()->triggerSmartContract(
            $abi,
            $contractHex,
            'transferFrom',
            [$fromHex, $toHex, $amount],
            $this->config['fee_limit'],
            $ownerHex
        );
        $signed = $this->tron->signTransaction($tx);
        $resp = $this->tron->sendRawTransaction($signed);
        return array_merge($resp, $signed);
    }
}
