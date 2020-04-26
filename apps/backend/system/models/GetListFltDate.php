<?php

namespace myerm\backend\system\models;

use myerm\backend\common\libs\SystemTime;
use myerm\backend\common\libs\StrTool;
/**
 * 
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author:陈鹭明 
 * @since:2015-12-3 10:36
 * @version: v2.0
 */

class GetListFltDate 
{
	public $sOper = "";
	public $iTypeDay = 0;
	public $iTypeWeek = 1;
	public $iTypeMonth = 2;
	public $sType = "date";
	public $lTimeOffset = null;
	 	
	/**
	 * 构造函数
	 * @return void
	 */
	function __construct($sType, $lTimeOffset)
	{
	    $this->sType = $sType;
	    $this->lTimeOffset = $lTimeOffset;
	}		 	
 	
	public function getThisYear($sFltINF) 
	{
        $sReturn = "";
        
        $iYear = SystemTime::getCurYear($this->lTimeOffset);
        
        $sMin = $iYear . "-01-01 00:00:00";
        $sMax = $iYear . "-12-31 23:59:59";
        
        if ($this->sType == 'unix') {
            $sMin = SystemTime::getTime($sMin, $this->lTimeOffset);
            $sMax = SystemTime::getTime($sMax, $this->lTimeOffset);
        }
        
        if (StrTool::equalsIgnoreCase($this->sOper, "equal")) {
            $sReturn = $sFltINF . ">='" . $sMin . "' AND " . $sFltINF . "<='" . $sMax . "'";
        } elseif (StrTool::equalsIgnoreCase($this->sOper, "smaller")) {
            $sReturn = $sFltINF . "<'" . $sMin . "'";
        } elseif (StrTool::equalsIgnoreCase($this->sOper, "larger")) {
            $sReturn = $sFltINF . ">'" . $sMax . "'";
        } elseif (StrTool::equalsIgnoreCase($this->sOper, "smeq")) {
            $sReturn = $sFltINF . "<='" . $sMax . "'";
        } elseif (StrTool::equalsIgnoreCase($this->sOper, "lgeq")) {
            $sReturn = $sFltINF . ">='" . $sMin . "'";
        } else {
            $sReturn = $sFltINF . ">='" . $sMin . "' AND " . $sFltINF . "<='" . $sMax . "'";
        }
		
		return $sReturn;
	} 	
	
	public function getThisWeek($sFltINF) 
	{
        $sReturn = "";
        
        $iMonth = SystemTime::getCurMonth($this->lTimeOffset);
        $iYear = SystemTime::getCurYear($this->lTimeOffset);
        $iDate = SystemTime::getCurDate($this->lTimeOffset);
        $iWeek = SystemTime::getCurWeekDay($this->lTimeOffset);
        
        $sMin = "";
        $sMax = "";
        
        $iTempWeek = 1;
        
        if ($iWeek - $iTempWeek == 0) {
            $iWeek = 0;
        } elseif ($iWeek < $iTempWeek) {
            $iWeek = $iWeek - $iTempWeek + 7;
        } elseif ($iWeek > $iTempWeek) {
            $iWeek = $iWeek - $iTempWeek;
        }
        
        $sMin = SystemTime::dateMove($iDate, - $iWeek, $this->lTimeOffset) . " 00:00:00";
        $sMax = SystemTime::dateMove($iDate, 7 - $iWeek - 1, $this->lTimeOffset) . " 23:59:59";
        
        if ($this->sType == 'unix') {
            $sMin = SystemTime::getTime($sMin, $this->lTimeOffset);
            $sMax = SystemTime::getTime($sMax, $this->lTimeOffset);
        }
        
        if (StrTool::equalsIgnoreCase($this->sOper, "equal")) {
            $sReturn = $sFltINF . ">='" . $sMin . "' AND " . $sFltINF . "<='" . $sMax . "'";
        } elseif (StrTool::equalsIgnoreCase($this->sOper, "smaller")) {
            $sReturn = $sFltINF . "<'" . $sMin . "'";
        } elseif (StrTool::equalsIgnoreCase($this->sOper, "larger")) {
            $sReturn = $sFltINF . ">'" . $sMax . "'";
        } elseif (StrTool::equalsIgnoreCase($this->sOper, "smeq")) {
            $sReturn = $sFltINF . "<='" . $sMax . "'";
        } elseif (StrTool::equalsIgnoreCase($this->sOper, "lgeq")) {
            $sReturn = $sFltINF . ">='" . $sMin . "'";
        } else {
            $sReturn = $sFltINF . ">='" . $sMin . "' AND " . $sFltINF . "<='" . $sMax . "'";
        }
        
        return $sReturn;
	} 	
	
	public function getThisQuarter($sFltINF) 
	{
        $sReturn = "";
        
        $iMonth = SystemTime::getCurMonth($this->lTimeOffset);
        $iYear = SystemTime::getCurYear($this->lTimeOffset);
        $i = 0;
        
        $sMin = "";
        $sMax = "";
        
        if ($iMonth < 4) {
            $i = 1;
        } elseif ($iMonth > 3 && $iMonth < 7) {
            $i = 2;
        } elseif ($iMonth > 6 && $iMonth < 10) {
            $i = 3;
        } elseif ($iMonth > 9 && $iMonth <= 12) {
            $i = 4;
        }
        
        if ($i == 1) {
            $sMin = $iYear . "-01-01 00:00:00";
            $sMax = $iYear . "-03-31 23:59:59";
        } elseif ($i == 2) {
            $sMin = $iYear . "-04-01 00:00:00";
            $sMax = $iYear . "-06-30 23:59:59";
        } elseif ($i == 3) {
            $sMin = $iYear . "-07-01 00:00:00";
            $sMax = $iYear . "-9-30 23:59:59";
        } elseif ($i == 4) {
            $sMin = $iYear . "-10-01 00:00:00";
            $sMax = $iYear . "-12-31 23:59:59";
        }
        
        if ($this->sType == 'unix') {
            $sMin = SystemTime::getTime($sMin, $this->lTimeOffset);
            $sMax = SystemTime::getTime($sMax, $this->lTimeOffset);
        }
        
        if (StrTool::equalsIgnoreCase($this->sOper, "equal")) {
            $sReturn = $sFltINF . ">='" . $sMin . "' AND " . $sFltINF . "<='" . $sMax . "'";
        } elseif (StrTool::equalsIgnoreCase($this->sOper, "smaller")) {
            $sReturn = $sFltINF . "<'" . $sMin . "'";
        } elseif (StrTool::equalsIgnoreCase($this->sOper, "larger")) {
            $sReturn = $sFltINF . ">'" . $sMax . "'";
        } elseif (StrTool::equalsIgnoreCase($this->sOper, "smeq")) {
            $sReturn = $sFltINF . "<='" . $sMax . "'";
        } elseif (StrTool::equalsIgnoreCase($this->sOper, "lgeq")) {
            $sReturn = $sFltINF . ">='" . $sMin . "'";
        } else {
            $sReturn = $sFltINF . ">='" . $sMin . "' AND " . $sFltINF . "<='" . $sMax . "'";
        }
        
        return $sReturn;
	}
	
	public function getThisMonth($sFltINF) 
	{
        $sReturn = "";
        
        $iMonth = SystemTime::getCurMonth($this->lTimeOffset);
        $iYear = SystemTime::getCurYear($this->lTimeOffset);
        $sMin = "";
        $sMax = "";
        
        $sMin = SystemTime::getCurYear($this->lTimeOffset) . "-" . $iMonth . "-01 00:00:00";
        
        if ($iMonth == 1 || $iMonth == 3 || $iMonth == 5 || $iMonth == 7 || $iMonth == 8 || $iMonth == 10 || $iMonth == 12) {
            $sMax = $iYear . "-" . $iMonth . "-31 23:59:59";
        } elseif ($iMonth == 4 || $iMonth == 6 || $iMonth == 9 || $iMonth == 11) {
            $sMax = $iYear . "-" . $iMonth . "-30 23:59:59";
        } elseif ($iMonth == 2) {
            if ($iYear % 4 == 0) {
                if ($iYear % 100 == 0 && $iYear % 400 != 0) {
                    $sMax = $iYear . "-" . $iMonth . "-28 23:59:59";
                } else {
                    $sMax = $iYear . "-" . $iMonth . "-29 23:59:59";
                }
            } else {
                $sMax = $iYear . "-" . $iMonth . "-28 23:59:59";
            }
        }
        
        if ($this->sType == 'unix') {
            $sMin = SystemTime::getTime($sMin, $this->lTimeOffset);
            $sMax = SystemTime::getTime($sMax, $this->lTimeOffset);
        }
        
        if (StrTool::equalsIgnoreCase($this->sOper, "equal")) {
            $sReturn = $sFltINF . ">='" . $sMin . "' AND " . $sFltINF . "<='" . $sMax . "'";
        } elseif (StrTool::equalsIgnoreCase($this->sOper, "smaller")) {
            $sReturn = $sFltINF . "<'" . $sMin . "'";
        } elseif (StrTool::equalsIgnoreCase($this->sOper, "larger")) {
            $sReturn = $sFltINF . ">'" . $sMax . "'";
        } elseif (StrTool::equalsIgnoreCase($this->sOper, "smeq")) {
            $sReturn = $sFltINF . "<='" . $sMax . "'";
        } elseif (StrTool::equalsIgnoreCase($this->sOper, "lgeq")) {
            $sReturn = $sFltINF . ">='" . $sMin . "'";
        } else {
            $sReturn = $sFltINF . ">='" . $sMin . "' AND " . $sFltINF . "<='" . $sMax . "'";
        }
        
        return $sReturn;
	} 	
	
	public function getToday($sFltINF) 
	{
        $sReturn = "";
        
        $sDate = SystemTime::getCurDate($this->lTimeOffset);
        
        $sMin = $sDate . " 00:00:00";
        $sMax = $sDate . " 23:59:59";
        
        if ($this->sType == 'unix') {
            $sMin = SystemTime::getTime($sMin, $this->lTimeOffset);
            $sMax = SystemTime::getTime($sMax, $this->lTimeOffset);
        }        
        
        if (StrTool::equalsIgnoreCase($this->sOper, "equal")) {
            $sReturn = $sFltINF . ">='" . $sMin . "' AND " . $sFltINF . "<='" . $sMax . "'";
        } elseif (StrTool::equalsIgnoreCase($this->sOper, "smaller")) {
            $sReturn = $sFltINF . "<'" . $sMin . "'";
        } elseif (StrTool::equalsIgnoreCase($this->sOper, "larger")) {
            $sReturn = $sFltINF . ">'" . $sMax . "'";
        } elseif (StrTool::equalsIgnoreCase($this->sOper, "smeq")) {
            $sReturn = $sFltINF . "<='" . $sMax . "'";
        } elseif (StrTool::equalsIgnoreCase($this->sOper, "lgeq")) {
            $sReturn = $sFltINF . ">='" . $sMin . "'";
        } else {
            $sReturn = $sFltINF . ">='" . $sMin . "' AND " . $sFltINF . "<='" . $sMax . "'";
        }
        
        return $sReturn;
	}
	
	public function getTomorrow($sFltINF) 
	{
        $sReturn = "";
        
        $sDate = SystemTime::getCurDate($this->lTimeOffset);
        
        $sMin = SystemTime::dateMove($sDate, 1, $this->lTimeOffset) . " 00:00:00";
        $sMax = SystemTime::dateMove($sDate, 1, $this->lTimeOffset) . " 23:59:59";
        
        if ($this->sType == 'unix') {
            $sMin = SystemTime::getTime($sMin, $this->lTimeOffset);
            $sMax = SystemTime::getTime($sMax, $this->lTimeOffset);
        }        
        
        if (StrTool::equalsIgnoreCase($this->sOper, "equal")) {
            $sReturn = $sFltINF . ">='" . $sMin . "' AND " . $sFltINF . "<='" . $sMax . "'";
        } elseif (StrTool::equalsIgnoreCase($this->sOper, "smaller")) {
            $sReturn = $sFltINF . "<'" . $sMin . "'";
        } elseif (StrTool::equalsIgnoreCase($this->sOper, "larger")) {
            $sReturn = $sFltINF . ">'" . $sMax . "'";
        } elseif (StrTool::equalsIgnoreCase($this->sOper, "smeq")) {
            $sReturn = $sFltINF . "<='" . $sMax . "'";
        } elseif (StrTool::equalsIgnoreCase($this->sOper, "lgeq")) {
            $sReturn = $sFltINF . ">='" . $sMin . "'";
        } else {
            $sReturn = $sFltINF . ">='" . $sMin . "' AND " . $sFltINF . "<='" . $sMax . "'";
        }
        
        return $sReturn;
	}

    public function getYesterday($sFltINF)
    {
        $sReturn = "";
        
        $sDate = SystemTime::getCurDate($this->lTimeOffset);
        
        $sMin = SystemTime::dateMove($sDate, - 1, $this->lTimeOffset) . " 00:00:00";
        $sMax = SystemTime::dateMove($sDate, - 1, $this->lTimeOffset) . " 23:59:59";
        
        if ($this->sType == 'unix') {
            $sMin = SystemTime::getTime($sMin, $this->lTimeOffset);
            $sMax = SystemTime::getTime($sMax, $this->lTimeOffset);
        }        
        
        if (StrTool::equalsIgnoreCase($this->sOper, "equal")) {
            $sReturn = $sFltINF . ">='" . $sMin . "' AND " . $sFltINF . "<='" . $sMax . "'";
        } elseif (StrTool::equalsIgnoreCase($this->sOper, "smaller")) {
            $sReturn = $sFltINF . "<'" . $sMin . "'";
        } elseif (StrTool::equalsIgnoreCase($this->sOper, "larger")) {
            $sReturn = $sFltINF . ">'" . $sMax . "'";
        } elseif (StrTool::equalsIgnoreCase($this->sOper, "smeq")) {
            $sReturn = $sFltINF . "<='" . $sMax . "'";
        } elseif (StrTool::equalsIgnoreCase($this->sOper, "lgeq")) {
            $sReturn = $sFltINF . ">='" . $sMin . "'";
        } else {
            $sReturn = $sFltINF . ">='" . $sMin . "' AND " . $sFltINF . "<='" . $sMax . "'";
        }
        
        return $sReturn;
	}	
	
	public function getManyMinute($sDate, $lMinute, $sDate1, $sDate2)
	{
		$sDate1 = SystemTime::dateAdd("minute", $lMinute, $sDate, $this->lTimeOffset);

		if ($this->sType == 'unix') {
		    $sDate1 = SystemTime::getTime($sDate1, $this->lTimeOffset);
		}		
		
		return [$sDate1, $sDate1];
	}
	
	public function getManyHours($sDate, $lHour, $sDate1, $sDate2)
	{
		$sDate1 = SystemTime::dateAdd("hour", $lHour, $sDate, $this->lTimeOffset);
		
		if ($this->sType == 'unix') {
		    $sDate1 = SystemTime::getTime($sDate1, $this->lTimeOffset);
		}
		
		return [$sDate1, $sDate1];
	}
	
	public function getManyDays($sDate, $lDays, $sDate1, $sDate2, $iType) 
	{
        $sReturn = "";
        $iMonth = SystemTime::getCurMonth($this->iTypeMonth);
        
        $iDate = "";
        if (!$sDate) {
            $iDate = SystemTime::getCurDate($this->iTypeMonth);
            if ($lDays > 0) {
                $iDate = SystemTime::dateMove($iDate, 1, $this->iTypeMonth);
                $lDays --;
            } else {
                $iDate = SystemTime::dateMove($iDate, - 1, $this->iTypeMonth);
                $lDays ++;
            }
        } else {
            $iDate = $sDate;
        }
        
        $iWeek = SystemTime::getCurWeekDay($this->iTypeMonth);
        
        $sMin = "";
        $sMax = "";
        
        if ($lDays > 0) {
            $sMax = SystemTime::dateMove($iDate, $lDays, $this->iTypeMonth);
            $sMin = ($iType == $this->iTypeDay) ? SystemTime::dateMove($iDate, 1, $this->iTypeMonth) : $iDate;
        } elseif ($lDays < 0) {
            $sMin = SystemTime::dateMove($iDate, $lDays, $this->iTypeMonth);
            $sMax = ($iType == $this->iTypeDay) ? SystemTime::dateMove($iDate, - 1, $this->iTypeMonth) : $iDate;
        } else {
            $sMin = $iDate;
            $sMax = $iDate;
        }
        
        if ($this->sType == 'unix') {
            $sMin = SystemTime::getTime($sMin, $this->lTimeOffset);
            $sMax = SystemTime::getTime($sMax, $this->lTimeOffset);
        }       
        
        return [$sMin, $sMax];
	}
	
	public function getManyMonths($lMonths, $sDate1, $sDate2) 
	{
        $sReturn = "";
        $iMonth = SystemTime::getCurMonth();
        $iYear = SystemTime::getCurYear();
        $sDate = "";
        $iMin = 0;
        $iMax = 0;
        $lAbsMon = 0;
        $b = true;
        
        if ($lMonths == 0) {
            $sLastDate = $iYear . "-" . ($iMonth + 1) . "-01  00:00:00";
            return [
                $iYear . "-" . $iMonth . "-01  00:00:00",
                SystemTime::dateMove($sLastDate, - 1) . " 23:59:59"
            ];
        }
        
        if ($lMonths < 0) {
            $lAbsMon = - $lMonths;
            $iMonth --;
            if ($iMonth == 2) {
                if ($iYear % 4 == 0) {
                    if ($iYear % 100 == 0 && $iYear % 400 != 0) {
                        $sDate = $iYear . "-" . "02-28";
                    } else {
                        $sDate = $iYear . "-" . "02-29";
                    }
                } else {
                    $sDate = $iYear . "-" . "02-28";
                }
            } elseif ($iMonth == 1 || $iMonth == 3 || $iMonth == 5 || $iMonth == 7 || $iMonth == 8 || $iMonth == 10) {
                $sDate = $iYear . "-" . $iMonth . "-31";
            } elseif ($iMonth == 4 || $iMonth == 6 || $iMonth == 9 || $iMonth == 11) {
                $sDate = $iYear . "-" . $iMonth . "-30";
            } elseif ($iMonth == 0) {
                $sDate = ($iYear - 1) . "-" . "12-31";
            }
            
            $b = false;
            $iMonth ++;
        } else {
            $lAbsMon = $lMonths;
            $iMonth ++;
            if ($iMonth == 13) {
                $sDate = ($iYear + 1) . "-01-01";
            } else {
                $sDate = $iYear . "-" . $iMonth . "-01";
            }
            
            $iMonth --;
        }
        
        for ($i = 1; $i < $lAbsMon + 1; $i ++) {
            if ($lMonths > 0) {
                if ($iMonth != 12) {
                    $iMonth ++;
                    
                    if ($iMonth == 1 || $iMonth == 3 || $iMonth == 5 || $iMonth == 7 || $iMonth == 8 || $iMonth == 10 || $iMonth == 12) {
                        $iMax = $iMax + 31;
                    } elseif ($iMonth == 4 || $iMonth == 6 || $iMonth == 9 || $iMonth == 11) {
                        $iMax = $iMax + 30;
                    } elseif ($iMonth == 2) {
                        if ($iYear % 4 == 0) {
                            if ($iYear % 100 == 0 && $iYear % 400 != 0) {
                                $sDate = $iYear . "-" . "02-28";
                                $iMax = $iMax + 28;
                            } else {
                                $sDate = $iYear . "-" . "02-29";
                                $iMax = $iMax + 29;
                            }
                        } else {
                            $iMax = $iMax + 28;
                        }
                    }
                } elseif ($iMonth == 12) {
                    $iYear = $iYear + 1;
                    $iMonth = 1;
                    $iMax = $iMax + 31;
                }
            } else {
                if ($iMonth != 1) {
                    $iMonth --;
                    
                    if ($iMonth == 1 || $iMonth == 3 || $iMonth == 5 || $iMonth == 7 || $iMonth == 8 || $iMonth == 10 || $iMonth == 12) {
                        $iMax = $iMax + 31;
                    } elseif ($iMonth == 4 || $iMonth == 6 || $iMonth == 9 || $iMonth == 11) {
                        $iMax = $iMax + 30;
                    } elseif ($iMonth == 2) {
                        if ($iYear % 4 == 0) {
                            if (iYear % 100 == 0 && iYear % 400 != 0) {
                                $sDate = $iYear . "-" . "02-28";
                                $iMax = $iMax + 28;
                            } else {
                                $sDate = $iYear . "-" . "02-29";
                                $iMax = $iMax + 29;
                            }
                        } else {
                            $iMax = $iMax + 28;
                        }
                    }
                } elseif ($iMonth == 1) {
                    $iYear = $iYear - 1;
                    $iMonth = 12;
                    $iMax = $iMax + 31;
                }
            }
        }
        
        if ($b) {
            return $this->getManyDays($sDate, $iMax - 1, $sDate1, $sDate2, $this->iTypeMonth);
        } else {
            return $this->getManyDays($sDate, - $iMax + 1, $sDate1, $sDate2, $this->iTypeMonth);
        }
	}
	
	public function getManyQuarter($sDate, $iMoveValue, $sDate1, $sDate2) 
	{
        $sReturn = "";
        $iMonth = SystemTime::getMonth($sDate, $this->iTypeMonth);
        $iYear = SystemTime::getYear($sDate, $this->iTypeMonth);
        $i = 0;
        
        $sMin = "";
        $sMax = "";
        
        if ($iMonth < 4) {
            $i = 1;
        } elseif ($iMonth > 3 && $iMonth < 7) {
            $i = 2;
        } elseif ($iMonth > 6 && $iMonth < 10) {
            $i = 3;
        } elseif ($iMonth > 9 && $iMonth <= 12) {
            $i = 4;
        }
        
        if ($iMoveValue > 0) {
            while ($iMoveValue != 0) {
                if ($i != 4) {
                    $i ++;
                } else {
                    $i = 1;
                }
                
                $iMoveValue --;
            }
        } else {
            while ($iMoveValue != 0) {
                if ($i != 1) {
                    $i --;
                } else {
                    $i = 4;
                }
                
                $iMoveValue ++;
            }
        }
        
        if ($i == 1) {
            $sMin = $iYear . "-01-01 00:00:00";
            $sMax = $iYear . "-03-31 23:59:59";
        } elseif ($i == 2) {
            $sMin = $iYear . "-04-01 00:00:00";
            $sMax = $iYear . "-06-30 23:59:59";
        } elseif ($i == 3) {
            $sMin = $iYear . "-07-01 00:00:00";
            $sMax = $iYear . "-9-30 23:59:59";
        } elseif ($i == 4) {
            $sMin = $iYear . "-10-01 00:00:00";
            $sMax = $iYear . "-12-31 23:59:59";
        }
        
        if ($this->sType == 'unix') {
            $sMin = SystemTime::getTime($sMin, $this->lTimeOffset);
            $sMax = SystemTime::getTime($sMax, $this->lTimeOffset);
        }        
        
        return [
            $sMin,
            $sMax
        ];
	}
	
	public function getManyWeeks($lWeeks, $sDate1, $sDate2) 
	{
        $sReturn = "";
        
        $iMonth = SystemTime::getCurMonth($this->iTypeMonth);
        $iYear = SystemTime::getCurYear($this->iTypeMonth);
        $iDate = SystemTime::getCurDate($this->iTypeMonth);
        $iWeek = SystemTime::getCurWeekDay($this->iTypeMonth);
        
        $sMin = "";
        $sMax = "";
        
        $iTempWeek = 1;
        
        if ($iWeek - $iTempWeek == 0) {
            $iWeek = 0;
        } elseif ($iWeek < $iTempWeek) {
            $iWeek = $iWeek - $iTempWeek + 7;
        } elseif ($iWeek > $iTempWeek) {
            $iWeek = $iWeek - $iTempWeek;
        }
        
        if ($lWeeks == 0) {
            return [
                SystemTime::dateMove($iDate, - $iWeek - 1, $this->iTypeMonth) . " 00:00:00",
                SystemTime::dateMove($iDate, 7 - $iWeek, $this->iTypeMonth) . " 23:59:59"
            ];
        }
        
        if ($lWeeks > 0) {
            $sDate = SystemTime::dateMove($iDate, 7 - $iWeek, $this->iTypeMonth);
            return $this->getManyDays($sDate, $lWeeks * 7 - 1, $sDate1, $sDate2, $this->iTypeWeek);
        } else {
            $sDate = SystemTime::dateMove($iDate, - $iWeek - 1, $this->iTypeMonth);
            $iDays = ($lWeeks * 7) + 1;
            return $this->getManyDays($sDate, $iDays, $sDate1, $sDate2, $this->iTypeWeek);
        }
	}
	
	public function get_FltPar($sFltValue, $sDate1, $sDate2)
	{
        $arrValue = explode("_", $sFltValue);
	
	    $iCounts = count($arrValue);
	    $sValue = strtolower($arrValue[0]);
	    $sPar1 = $arrValue[1];
	    
        if ($sValue == "this") {
            $sThisValue = strtolower($sPar1);
            if ($sThisValue == "year") {
                $sDate1 = intval(SystemTime::getCurYear()) . "-01-01 00:00:00";
                $sDate2 = intval(SystemTime::getCurYear()) . "-12-31 23:59:59";
            } elseif ($sThisValue == "month") {
                $iMonth = intval(SystemTime::getCurMonth());
                $iYear = intval(SystemTime::getCurYear());
                $sDate1 = $iYear . "-" . $iMonth . "-01 00:00:00";
                if ($iMonth == 12) {
                    $sDate2 = $iYear . "-12-31 23:59:59";
                } else {
                    $iMonth ++;
                    $sDate = $iYear . "-" . $iMonth . "-01";
                    $sDate2 = SystemTime::dateMove($sDate, - 1) . " 23:59:59";
                }
            } elseif ($sThisValue == "quarter") {
                $arrDate = $this->getManyQuarter(SystemTime::getCurDate(), 0, $sDate1, $sDate2);
            } elseif ($sThisValue == "week") {
                $arrDate = $this->getManyWeeks(0, $sDate1, $sDate2);
            } elseif ($sThisValue == "date") {
                $sDate1 = SystemTime::getCurDate() . " 00:00:00";
                $sDate2 = SystemTime::getCurDate() . " 23:59:59";
            }
        } elseif ($sValue == "last" || $sValue == "next") {//前
            $iValue = intval($sPar1);
            
            // 如果是前则取反
            if ($sValue == "last") {
                $iValue = - $iValue;
            }
            
            $sThisValue = strtolower($arrValue[2]);
            
            if ($sThisValue == "year") {
                $sThisValue = intval(SystemTime::getCurYear()) + $iValue;
                $sDate1 = $sThisValue . "-01-01 00:00:00";
                $sDate2 = $sThisValue . "-12-31 23:59:59";
            } elseif ($sThisValue == "month") {
                $arrDate = $this->getManyMonths($iValue, $sDate1, $sDate2);
            } elseif ($sThisValue == "quarter") {
                $arrDate = $this->getManyQuarter(SystemTime::getCurDate(), $iValue, $sDate1, $sDate2);
            } elseif ($sThisValue == "week") {
                $arrDate = $this->getManyWeeks($iValue, $sDate1, $sDate2);
            } elseif ($sThisValue == "date") {
                $arrDate = $this->getManyDays(SystemTime::getCurDate(), $iValue, $sDate1, $sDate2, iTypeDay);
            } elseif ($sThisValue == "hour") {
                $arrDate = $this->getManyHours(SystemTime::getCurLongDate(), $iValue, $sDate1, $sDate2);
            } elseif ($sThisValue == "minute") {
                $arrDate = $this->getManyMinute(SystemTime::getCurLongDate(), $iValue, $sDate1, $sDate2);
            }
        } elseif ($sValue == "someyear") {//某年
            $sDate1 = $sPar1 . "-01-01 00:00:00";
            $sDate2 = $sPar1 . "-12-31 23:59:59";
        } elseif ($sValue == "somemonth") {//某月
            $sDate1 = $sPar1 . "-01 00:00:00";
            
            $arrPart = explode("-", $sPar1);
            $sYear = $arrPart[0];
            $sMonth = $arrPart[1];
            
            $iMonth = intval($sMonth);
            if ($iMonth == 12) {
                $sDate2 = $sYear . "-12-31 23:59:59";
            } else {
                $iMonth ++;
                $sDate = $sYear . "-" . $iMonth . "-01";
                $sDate2 = SystemTime::dateMove($sDate, - 1) . " 23:59:59";
            }
        } elseif ($sValue == "somedate") {//某日
            // 如果有时分秒
            if (StrTool::indexOf($sPar1, " ") > 0) {
                // 取得时分秒
                $date = substr($sPar1, 0, StrTool::indexOf($sPar1, " "));
                $time = substr($sPar1, StrTool::indexOf($sPar1, " ") + 1);
                $hour = substr($sPar1, 0, StrTool::indexOf($time, ":"));
                $sDate1 = $date . " " . $hour . ":00:00";
                $sDate2 = $date . " " . $hour . ":59:59";
            } else {
                $sDate1 = $sPar1 . " 00:00:00";
                $sDate2 = $sPar1 . " 23:59:59";
            }
        } elseif ($sValue == "sometime") {//某时
            $sDate1 = $sPar1;
            $sDate2 = $sPar1;
        }
        
        if ($arrDate[0] && StrTool::indexOf($arrDate[0], " ") == - 1) {
            $sDate1 = $arrDate[0] . " 00:00:00";
            $sDate2 = $arrDate[1] . " 23:59:59";
        }
        
        if ($this->sType == 'unix') {
            $sDate1 = SystemTime::getTime($sDate1, $this->lTimeOffset);
            $sDate2 = SystemTime::getTime($sDate2, $this->lTimeOffset);
        }        

	    return [$sDate1, $sDate2];
	}
}