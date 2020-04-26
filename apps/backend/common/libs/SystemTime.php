<?php

namespace myerm\backend\common\libs;

use Yii;


/**
 * 系统时间类
 * ============================================================================
 * 版权所有 2010-2017 三只象（厦门）网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.myerm.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author:陈鹭明 
 * @since:2010-6-2 22:12
 * @version: v1.2
 */

class SystemTime 
{
	/**
	 * 日期格式
	 * @var string
	 */
	const dateFormat = "Y-m-d";
	
	/**
	 * 时间格式
	 * @var string
	 */
	const timeFormat = "H:i:s";	
	
	/**
	 * 时区
	 * @var string
	 */
	const timeZone = 8;		
	
	/**
	 * 获取指定$sTimeZone的时区，如果$sTimeZone为空，则获取系统默认的时区
	 * @param String $sTimeZone
	 */
	public static function getTimeOffset($sTimeZone=null)
	{
	    $date = new \DateTime('now', new \DateTimeZone($sTimeZone ? $sTimeZone : Yii::$app->timeZone));
	    return $date->getOffset()/3600;	    
	}
	
	/**
	 * 取得指定日期中的天，缺省取当前时间。
	 * @return String
	 */
	public static function getDay($date=0, $lTimeOffset=null) 
	{
	    if (StrTool::isEmpty($lTimeOffset)) {
	        $lTimeOffset = self::getTimeOffset();
	    }
	    
		return gmdate("j", self::getTime($date, $lTimeOffset) + $lTimeOffset * 3600);
	}	
	
	/**
	 * 取得指定日期中的月，缺省取当前时间。
	 * @return String
	 */
	public static function getMonth($date=0, $lTimeOffset=null) 
	{
	    if (StrTool::isEmpty($lTimeOffset)) {
	        $lTimeOffset = self::getTimeOffset();
	    }	    
	    
		return gmdate("n", self::getTime($date, $lTimeOffset) + $lTimeOffset * 3600);
	}
	
	/**
	 * 取得指定日期中的季，缺省取当前时间。
	 * @return String
	 */
	public static function getSeason($date=0, $lTimeOffset=null) 
	{
	    if (StrTool::isEmpty($lTimeOffset)) {
	        $lTimeOffset = self::getTimeOffset();
	    }	    
	    
		return ceil(self::getMonth($date, $lTimeOffset)/3);
	}
	
	/**
	 * 取得指定日期中的年，缺省取当前时间。
	 * @return String
	 */
	public static function getYear($date=0, $lTimeOffset=null) 
	{
	    if (StrTool::isEmpty($lTimeOffset)) {
	        $lTimeOffset = self::getTimeOffset();
	    }
	    
		return gmdate("Y", self::getTime($date, $lTimeOffset) + $lTimeOffset * 3600);
	}
	
	
	/**
	 * 取得指定日期中的年和年，缺省取当前时间。
	 * @return String
	 */
	public static function getYearMonth($date=0, $lTimeOffset=null)
	{
	    if (StrTool::isEmpty($lTimeOffset)) {
	        $lTimeOffset = self::getTimeOffset();
	    }
	    
		return gmdate("Y-m", self::getTime($date, $lTimeOffset) + $lTimeOffset * 3600);
	}	
	
	/**
	 * 取得指定日期中的月和日，缺省取当前时间。
	 * @return String
	 */
	public static function getMonthDay($date=0, $lTimeOffset=null)
	{
	    if (StrTool::isEmpty($lTimeOffset)) {
	        $lTimeOffset = self::getTimeOffset();
	    }
	    
	    return gmdate("m-d", self::getTime($date, $lTimeOffset) + $lTimeOffset * 3600);
	}	
	
	
	/**
	 * 取得指定日期中的周几，缺省取当前时间。
	 * @return String
	 */
	public static function getWDay($date=0, $lTimeOffset=null) 
	{
	    if (StrTool::isEmpty($lTimeOffset)) {
	        $lTimeOffset = self::getTimeOffset();
	    }	    
	    
		$datearr = getdate(self::getTime($date, $lTimeOffset) + $lTimeOffset * 3600);
		return intval($datearr['wday']);
	}		
	
	
	/**
	 * 取得指定日期中的小时，缺省取当前时间。
	 * @return String
	 */
	public static function getHour($date=0, $lTimeOffset=null) 
	{
	    if (StrTool::isEmpty($lTimeOffset)) {
	        $lTimeOffset = self::getTimeOffset();
	    }	    
	    
		$datearr = getdate(self::getTime($date, $lTimeOffset) + $lTimeOffset * 3600);
		
		if(intval($datearr['hours']) < 10)
		{
			return "0".intval($datearr['hours']);
		}
		else 
		{
			return intval($datearr['hours']);
		}		
	}		
	
	/**
	 * 取得指定日期中的分钟，缺省取当前时间。
	 * @return String
	 */	
	public static function getMinute($date=0, $lTimeOffset=null) 
	{
	    if (StrTool::isEmpty($lTimeOffset)) {
	        $lTimeOffset = self::getTimeOffset();
	    }	    
	    
		$datearr = getdate(self::getTime($date, $lTimeOffset) + $lTimeOffset * 3600);
		
		if(intval($datearr['minutes']) < 10)
		{
			return "0".intval($datearr['minutes']);
		}
		else 
		{
			return intval($datearr['minutes']);
		}
	}		
	
	
	public static function getSecond($date=0, $lTimeOffset=null) 
	{
	    if (StrTool::isEmpty($lTimeOffset)) {
	        $lTimeOffset = self::getTimeOffset();
	    }	    
	    
		$datearr = getdate(self::getTime($date, $lTimeOffset) + $lTimeOffset * 3600);
		return intval($datearr['seconds']);
	}		
	
	/**
	 * 取得当前日期。
	 * @return String
	 */
	public static function getCurDate($lTimeOffset=null) 
	{
	    if (StrTool::isEmpty($lTimeOffset)) {
	        $lTimeOffset = self::getTimeOffset();
	    }	    
	    
		return self::getShortDate(time(), $lTimeOffset);
	}

	/**
	 * 取得当前时间。
	 * @return String
	 */
	public static function getCurTime($lTimeOffset) 
	{
	    if (StrTool::isEmpty($lTimeOffset)) {
	        $lTimeOffset = self::getTimeOffset();
	    }	    
	    
		return gmdate(self::timeFormat, self::getTime(time(), $lTimeOffset) + $lTimeOffset * 3600);
	}

	
	/**
	 * 取得当前长型日期。
	 * @return String
	 */
	public static function getCurLongDate($lTimeOffset=null) 
	{
	    
		return self::getLongDate(time(), $lTimeOffset);
	}
	
	
	/**
	 * 取得当前年。
	 * @return String
	 */
	public static function getCurYear($lTimeOffset=null) 
	{
		return self::getYear(time(), $lTimeOffset);
	}	
	
	/**
	 * 取得当前月。
	 * @return String
	 */
	public static function getCurMonth($lTimeOffset=null) 
	{
	    if (StrTool::isEmpty($lTimeOffset)) {
	        $lTimeOffset = self::getTimeOffset();
	    }	    
	    
		return self::getMonth(time(), $lTimeOffset);
	}

	/**
	 * 取得当前日。
	 * @return String
	 */
	public static function getCurDay($lTimeOffset=null) 
	{
	    if (StrTool::isEmpty($lTimeOffset)) {
	        $lTimeOffset = self::getTimeOffset();
	    }	    
	    
		return self::getDay(time(), $lTimeOffset);
	}	
	
	/**
	 * 根据给定的时间，进行格式化处理。
	 * @return String
	 */
	public static function getShortDate($time=0, $lTimeOffset=null) 
	{
	    if (StrTool::isEmpty($lTimeOffset)) {
	        $lTimeOffset = self::getTimeOffset();
	    }
	    
		return gmdate(self::dateFormat, self::getTime($time, $lTimeOffset) + $lTimeOffset * 3600);
	}	
	
	/**
	 * 根据给定的时间，进行格式化处理。
	 * @return String
	 */
	public static function getLongDate($time=0, $lTimeOffset=null) 
	{
	    if (StrTool::isEmpty($lTimeOffset)) {
	        $lTimeOffset = self::getTimeOffset();
	    }

		return gmdate(self::dateFormat." ".self::timeFormat, self::getTime($time, $lTimeOffset) + $lTimeOffset * 3600);
	}
	
	
	/**
	 * 取得当前周几。
	 */
	public static function getCurWeekDay($lTimeOffset=null) 
	{
		return self::getWDay(time(), $lTimeOffset);
	}	
	
	
	/**
	 * 获取某周的第一天，周日
	 */
	public static function getFirstDayOfWeek($date=0, $lTimeOffset=null)
	{
	    if (StrTool::isEmpty($lTimeOffset)) {
	        $lTimeOffset = self::getTimeOffset();
	    }	    
	    
		return SystemTime::getShortDate(self::getTime($date, $lTimeOffset) - (self::getWDay($date, $lTimeOffset)) * 86400);//周日
	}
	
	/**
	 * 获取某周的最后一天，周六
	 */
	public static function getLastDayOfWeek($date=0, $lTimeOffset=null)
	{
	    if (StrTool::isEmpty($lTimeOffset)) {
	        $lTimeOffset = self::getTimeOffset();
	    }
	     	    
		return SystemTime::getShortDate(self::getTime($date, $lTimeOffset) + (6 - self::getWDay($date, $lTimeOffset)) * 86400);//周六
	}
	
	/**
	 * 获取某月的第一天
	 */
	public static function getFirstDayOfMonth($date=0, $lTimeOffset=null)
	{
	    if (StrTool::isEmpty($lTimeOffset)) {
	        $lTimeOffset = self::getTimeOffset();
	    }
	     	    
		return self::getYear($date, $lTimeOffset)."-".self::getMonth($date, $lTimeOffset)."-1";
	}
	
	/**
	 * 获取某月的最后一天
	 */
	public static function getLastDayOfMonth($date=0, $lTimeOffset=null)
	{
	    if (StrTool::isEmpty($lTimeOffset)) {
	        $lTimeOffset = self::getTimeOffset();
	    }
	     	    
		return self::getYear($date, $lTimeOffset)."-".self::getMonth($date, $lTimeOffset)."-".gmdate("t", self::getTime($date, $lTimeOffset) + $lTimeOffset * 3600);
	}	
	
	/**
	 * 下一个月
	 * @return Date
	 */
	public static function getNextMonth($date=0, $lTimeOffset=null)
	{
	    if (StrTool::isEmpty($lTimeOffset)) {
	        $lTimeOffset = self::getTimeOffset();
	    }	    
	    
		$Year = self::getYear($date, $lTimeOffset);
		$Month = self::getMonth($date, $lTimeOffset) + 1;
		
		if($Month == 13)
		{
			$Year++;
			$Month = 1;
		}
		
		return "$Year-$Month-1";
	}
		
	/**
	 * 前一个月
	 * @return Date
	 */
	public static function getPreviousMonth($date=0, $lTimeOffset=null)
	{
	    if (StrTool::isEmpty($lTimeOffset)) {
	        $lTimeOffset = self::getTimeOffset();
	    }	    
	    
		$Year = self::getYear($date, $lTimeOffset);
		$Month = self::getMonth($date, $lTimeOffset) - 1;
		
		if($Month == 0)
		{
			$Year--;
			$Month = 12;
		}
		
		return "$Year-$Month-1";
	}	
	
	/**
	 * 获取周某天的中文名：一、二、日、六。。。。。。。
	 */
	public static function getWDayChinese($date=0)
	{
		return self::getLocalWeekDay(SystemTime::getWDay($date));
	}
	
	public static function getLocalWeekDay($lWDay) 
	{
		import("models.common.i18n.I18nMessage");
		$i18n = new I18nMessage();		
		
		switch ($lWDay)
		{
			case 0:
				return $i18n->getLocalizedString("周日");
			case 1:
				return $i18n->getLocalizedString("周一");				
			case 2:
				return $i18n->getLocalizedString("周二");				
			case 3:
				return $i18n->getLocalizedString("周三");				
			case 4:
				return $i18n->getLocalizedString("周四");				
			case 5:
				return $i18n->getLocalizedString("周五");				
			case 6:
				return $i18n->getLocalizedString("周六");				
		}		
		
		return NULL;
	}
	
	/**
	 * 返回Unix时间戳，允许接受日期，Unix时间戳格式的参数。注意如果传值是Unix时间戳，必须是以格林尼治时区为准。
	 */
	public static function getTime($time=0, $lTimeOffset=null)
	{
		try
		{
			if(!$time)
			{
				return time();
			}
			elseif(preg_match("/^[0-9]{5,}$/", $time))
			{//判断是否数值型，即UNIX时间戳
				return $time;
			}
			elseif(strtotime($time))
			{//判断是否标准日期型，转换成UNIX时间戳
			    
			    if (StrTool::isEmpty($lTimeOffset)) {
			        $lTimeOffset = SystemTime::getTimeOffset();
			    }
			    
				return strtotime($time) + (SystemTime::getTimeOffset() - $lTimeOffset) * 3600;
			}
			else
			{
				throw new Exception("对不起，{$time}既不是标准的UNIX时间戳也不是标准的日期格式。");
			}
		}
		catch( Exception $e)
		{
			return 0;
		}		
	}
	
	/**
	 * 移动日期（天）。
	 */
	public static function dateMove($sCurDate, $iDays, $lTimeOffset=null) 
	{
	    if (StrTool::isEmpty($lTimeOffset)) {
	        $lTimeOffset = SystemTime::getTimeOffset();
	    }	    
	    
		$lD = self::getTime($sCurDate, $lTimeOffset);
		$ll = 1;
		$ll = $ll * $iDays  * 86400;
		$lD = $lD + $ll;
		
		return self::getShortDate($lD, $lTimeOffset);
	}	
	
	/**
	 * 类似于SQLServer的DateAdd函数
	 * @param datePart year,month,week,day,hour,minute,second
	 * @param lNumber 加多少

	 * @param date 时间起点
	 * @return 计算后的时间
	 * @throws ValidateException 时间格式不对或者datepart不对
	 */
	public static function dateAdd($datePart, $lNumber, $date, $lTimeOffset=null) 
	{
		try 
		{
		    if (StrTool::isEmpty($lTimeOffset)) {
		        $lTimeOffset = SystemTime::getTimeOffset();
		    }		    
		    
			if (StrTool::indexOf($date, " ") == -1) 
			{
				$date .= " 00:00:00";
			}
			
			$time = 0;
			if (StrTool::equalsIgnoreCase($datePart, "hour")) 
			{
				$time = self::getTime($date, $lTimeOffset);
				$time = $time + $lNumber * 60 * 60;
			} 
			else if (StrTool::equalsIgnoreCase($datePart, "minute")) 
			{
				$time = self::getTime($date, $lTimeOffset);
				$time = $time + $lNumber * 60;
			} 
			else 
			{
				throw new ValidateException("Invalid DatePart : " . $datePart);
			}
			
			return self::getLongDate($time, $lTimeOffset);
		} 
		catch (Exception $e) 
		{
			throw new ValidateException("Invalid Date String : " . $date);
		}
	}	
	
	/**
	 * 比较$date1和$date2，返回二者时间戳的差
	 * @param String $date1
	 * @param String $date2
	 */
	public static function compareTo($date1, $date2)
	{
		return self::getTime($date1) - self::getTime($date2);
	}
	
	public static function getThisWeek($date, $lTimeOffset=null) 
	{
	    if (StrTool::isEmpty($lTimeOffset)) {
	        $lTimeOffset = self::getTimeOffset();
	    }	    
	    
		$sReturn = "";

		$iWeek = SystemTime::getWDay($date, $lTimeOffset);

		$sMin = "";
		$sMax = "";

		$iTempWeek = intval(CommonCache::get("myermcache-2-week"));

		if ($iWeek - $iTempWeek == 0) 
		{
			$iWeek = 0;
		} 
		else if ($iWeek < $iTempWeek) 
		{
			$iWeek = $iWeek - $iTempWeek . 7;
		} 
		else if ($iWeek > $iTempWeek) 
		{
			$iWeek = $iWeek - $iTempWeek;
		}

		$sMin = SystemTime::dateMove($date, -$iWeek, $lTimeOffset);
		$sMax = SystemTime::dateMove($date, 7 - $iWeek - 1, $lTimeOffset);

		return array($sMin, $sMax);
	} 		
	
	
	/*
	 * 友好显示日期
	 */
	public static function friendlyShow($date)
	{
		if(!$date)
		{
			return $date;
		}

		import("models.common.i18n.I18nMessage");
		$i18n = new I18nMessage();			
		
		$lTime = self::getTime($date);
		
		$lShort = true;
		if(strstr($date, ":"))
		{
			$lShort = false;
		}
		
		//如果是今天
		$sToday = self::getShortDate();
		if($lTime >= self::getTime($sToday.' 00:00:00') && $lTime <= self::getTime($sToday.' 23:59:59'))
		{
			return "<span title='$date'>".$i18n->getLocalizedString("今天").(!$lShort ? " ".self::getHour($date).':'.self::getMinute($date) : "")."</span>";
		}
		
		//如果是昨天
		$sYestoday = self::dateMove(0, -1);
		if($lTime >= self::getTime($sYestoday.' 00:00:00') && $lTime <= self::getTime($sYestoday.' 23:59:59'))
		{
			return "<span title='$date'>".$i18n->getLocalizedString("昨天").(!$lShort ? " ".self::getHour($date).':'.self::getMinute($date) : "")."</span>";
		}
		
		//如果是前天
		$sTheDayBeforeYestoday = self::dateMove(0, -2);
		if($lTime >= self::getTime($sTheDayBeforeYestoday.' 00:00:00') && $lTime <= self::getTime($sTheDayBeforeYestoday.' 23:59:59'))
		{
			return "<span title='$date'>".$i18n->getLocalizedString("前天").(!$lShort ? " ".self::getHour($date).':'.self::getMinute($date) : "")."</span>";
		}		
		
		//如果在本周
		$arrWeek = self::getThisWeek(0);
		if($lTime >= self::getTime($arrWeek[0].' 00:00:00') && $lTime <= self::getTime($arrWeek[1].' 23:59:59'))
		{
			return "<span title='$date'>".self::getWDayChinese($date).(!$lShort ? " ".self::getHour($date).':'.self::getMinute($date) : "")."</span>";
		}			

		//如果在上周
		$arrLastWeek = array(self::dateMove($arrWeek[0], -7), self::dateMove($arrWeek[1], -7));
		if($lTime >= self::getTime($arrLastWeek[0].' 00:00:00') && $lTime <= self::getTime($arrLastWeek[1].' 23:59:59'))
		{
			return "<span title='$date'>".$i18n->getLocalizedString("上").self::getWDayChinese($date).(!$lShort ? " ".self::getHour($date).':'.self::getMinute($date) : "")."</span>";
		}
		
		//如果是今年
		$iYear = self::getCurYear();
		if($lTime >= self::getTime($iYear . "-01-01 00:00:00") && $lTime <= self::getTime($iYear . "-12-31 23:59:59"))
		{
			return "<span title='$date'>".self::getMonth($date)."-".self::getDay($date).(!$lShort ? " ".self::getHour($date).':'.self::getMinute($date) : "")."</span>";
		}
		
		
		return $date;
	}
	
	
	/*
	 * 是否闰年
	 */
	public static function isRenYear($sDate) 
	{
		try 
		{
			$s = $sDate;
			$iYear = substr($s, 0, 4);

			if ($iYear % 4 == 0) 
			{
				if ($iYear % 100 == 0 && $iYear % 400 != 0) 
				{
					return false;
				} 
				else
				{
					return true;
				}
			}
			
			return false;
		} 
		catch (Exception $e) 
		{
			return false;
		}
	}	
}