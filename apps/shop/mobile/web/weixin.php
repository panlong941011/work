<?php
/**
 * weixin 微信关注公众号，微信官方回调该页面
 * TOKEN  用于与微信公众号设置的 token进行验证
 */
define('IN_ECS', true);
header('Content-type:text/html;charset=utf-8');
//定义token
define("TOKEN", "WxIndex");

//实例对象
$wechatObj = new wechatCallbackapiTest();
//判断是否需要token验证，如果需要则进行验证，如果不需要则进行消息回复
if (isset($_GET["echostr"])) {
	//验证token
	$wechatObj->valid();
} else {
	$wechatObj->responseMsg();
}

class wechatCallbackapiTest
{

	//来三斤
//	const  APPID = 'wx8f5d0856fdd96872';
//	const  APPSECRET = '714e784aeb0b9a3b428a45df6adba403';
	

	
	
	/**                     新正试站数据库链接                             */
	//数据库地址
//	const  HOST = 'rm-uf6fij86zav810r9f.mysql.rds.aliyuncs.com';
//	//前台数据库
//	const  USERNAME = 'lsjroot';
//	const  PASSWORD = 'R5v4eR155V7KdVDv';
//	const  DBNAME = 'laisanjincom';
//	//后台数据库
//	const  MYERM_USERNAME = "lsjroot";
//	const  MYERM_PASSWORD = 'R5v4eR155V7KdVDv';
//	const  MYERM_DBNAME = 'laisanjinmyerm';
	/** **************************************************************/
	

	
	private $access_token = '';
	
	/**************************** 验证Token令牌 *********************************/
	public function valid()
	{
		$echoStr = $_GET["echostr"];
		
		if ($this->checkSignature()) {
			echo $echoStr;
			exit;
		}
	}
	
	//检验签名是否是来自微信服务器
	private function checkSignature()
	{
		// 检测是否已经有定义了token的常量，如果没定义则抛出错误，告知未定义token
		if (!defined("TOKEN")) {
			throw new Exception('TOKEN is not defined!');
		}
		
		$signature = $_GET["signature"];//获取前面
		$timestamp = $_GET["timestamp"];//获取时间戳
		$nonce = $_GET["nonce"];//获取随机数
		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);//组成数组
		// use SORT_STRING rule
		sort($tmpArr, SORT_STRING);//对新数组进行排序
		$tmpStr = implode($tmpArr);//将数组组成字符串
		$tmpStr = sha1($tmpStr);//对字符串进行shal加密
		
		if ($tmpStr == $signature) {
			return true;
		} else {
			return false;
		}
	}
	
	/******************************** END *******************************************/
	
	
	private function getAccessToken()
	{
		$ip = "r-uf6a38a59b4683c4.redis.rds.aliyuncs.com";
		$port = 6379;
		$redis = new \Redis();
		$redis->pconnect($ip, $port, 1);
		$redis->auth("alT1g4PqxppThEzp");
		//获取微信令牌
		$accesstoken = $redis->get('m_accesstoken');
		$redis->close();
		return $accesstoken;
	}
	
	
	/**
	 * 推送消息
	 * @author ldz
	 * @Time 2017-11-7 19:41:39
	 */
	public function responseMsg()
	{
		//获取微信服务器端发送过来的数据（该数据格式可能是php无法识别的，如test/xml格式，所以最好采用HTTP_RAW_POST_DATA这种格式来接收）
		$postStr = file_get_contents("php://input");
		//对接收到的xml包进行解析
		$bSaveResponseMsg = false;//是否保存推送记录
		if (!empty($postStr)) {
			/* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
			   the best way is to check the validity of xml by yourself */
			//libxml_disable_entity_loader做安全防御用的：对于PHP，由于simplexml_load_string 函数的XML解析问题出现在libxml库上，所以加载实体前可以调用这样一个函数
			libxml_disable_entity_loader(true);
			//加载xml数据
			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			//$this->sendTextMsg($postObj->FromUserName, '来三斤欢迎您！');
			//消息类型
			if ($postObj->MsgType == "event") {
				$bAccess = true;
				//防止重复扫描
				if ($bAccess) {
					$bAccess = false;
					//推送关注消息，点击事件
					$this->responseEventMsg($postObj);
						/*事物结束*/
					}
					

			} else if ($postObj->MsgType == "image") {
			
			}
			if ($bSaveResponseMsg) {
				self::saveResponseMsg($postObj);
			}
		} else {
			echo "Please input something!";
			exit;
		}
	}
	
	/**
	 * 保存事件回调消息
	 * @param $postObj
	 * @author ouyangyz <ouyangyanzhong@163.com>
	 * @time 2019/1/23 11:43
	 */
	public function saveResponseMsg($postObj)
	{

	}
	
	/***邀请开店，推送图文消息***/
	public function userapply($openid, $inviteID, $isAgent)
	{
		//判断谁已经是经销商
		if ($isAgent) {
			$url = 'http://' . $_SERVER["HTTP_HOST"] . '/lsj';
			$imgUrl = "http://m.laisanjin.cn/images/new4/inviteOpenShop20181212.jpg";
			$title = '欢迎回来';
			$description = '点击进入店铺';
			$this->sendMsg($openid, $url, $imgUrl, $title, $description);
		} else {
			$url = 'http://' . $_SERVER["HTTP_HOST"] . '/shop' . $inviteID . '/member/bindmobile';
			$imgUrl = "http://m.laisanjin.cn/images/new4/inviteOpenShop20181212.jpg";
			$title = '来三斤邀您一起共创未来';
			$description = '点击进入开店';
			$this->sendMsg($openid, $url, $imgUrl, $title, $description);
		}
	}
	
	/***邀请好友***/

	
	/***红包***/
	public function redpacket($con, $openid, $UserID, $redID)
	{
		//查询红包相关信息
		$sql = "SELECT endDate,statusID,sRedPackedID,lID,money,lRedbatch FROM RedPacket WHERE sRedPackedID = '" . $redID . "'";
		$result = $con->query($sql);
		if ($result) {
			$redpacket = $result->fetch_row();
			$endDate = strtotime($redpacket[0]);   //红包结束之间
			$difTime = time() - $endDate;      //当前时间-红包时间的差值
			$newTime = time();
			//状态为2获取当前时间大于结束时间，说明该红包已失效
			if ($redpacket[1] == 2 || $difTime > 0) {
				$this->sendTextMsg($openid, '该红包已失效');
				return false;
			}
			
			//查询红包是谁领取的
			$redSql = "SELECT UserID FROM `ReceiveRedPacket` WHERE lRedPacketID = '" . $redID . "'";
			$ReceiveRed = $con->query($redSql);
			if ($ReceiveRed) {
				$ReceiveRed = $ReceiveRed->fetch_row();
			} else {
				$ReceiveRed = '';
			}
			
			//判断扫描的人是不是领走该红包的人，是的话再发一遍图文消息，否则执行else语句
			if (!empty($ReceiveRed)) {
				if ($UserID == $ReceiveRed[0]) {
					if ($redpacket[1] > 0) {
						$title = '该红包已领取';       //标题
						$description = '';     //描述
						$url = 'http://' . $_SERVER['HTTP_HOST'] . '/lsj/site/voucher?category=redpacket&randID=' . $redID . '&UserID=' . $UserID;                                                                                //图片跳转链接
						$imgUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/images/redpacket/redbag.jpg';  //推送界面的图片
						$this->sendMsg($openid, $url, $imgUrl, $title, $description);
						return false;
					} else {
						$title = '该红包您未领取';       //标题
						$description = '';     //描述
						$url = 'http://' . $_SERVER['HTTP_HOST'] . '/lsj/site/voucher?category=redpacket&randID=' . $redID . '&UserID=' . $UserID;                                                                                //图片跳转链接
						$imgUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/images/redpacket/redbag.jpg';  //推送界面的图片
						$this->sendMsg($openid, $url, $imgUrl, $title, $description);
						return false;
					}
				} else {
					$this->sendTextMsg($openid, '该红包已被别人领走了');
					return false;
				}
			} else {//判断红包领取表是否未创建，未创建则进去创建，已创建则跳过
				$NowTime = gmdate('Y-m-d H:i:s', time() + 8 * 3600);
				$addSql = "INSERT INTO `ReceiveRedPacket` (`OwnerID`,`EditUserID`,`dNewDate`,`UserID`,`statusID`,`lRedPacketID`,`sRedPacketName`,`fMoney`,`lRedbatch`)
VALUES('5','" . $UserID . "','" . $NowTime . "','" . $UserID . "','0','" . $redID . "','" . $redpacket[3] . "','" . $redpacket[4] . "','" . $redpacket[5] . "')";
				if ($con->query($addSql)) {
					//推送
					$title = '您有红包，尽快领取哦';       //标题
					$description = '红包来了，买买买';     //描述
					$url = 'http://' . $_SERVER['HTTP_HOST'] . '/lsj/site/voucher?category=redpacket&randID=' . $redID . '&UserID=' . $UserID;                                                                                //图片跳转链接
					$imgUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/images/redpacket/redbag.jpg';  //推送界面的图片
					$this->sendMsg($openid, $url, $imgUrl, $title, $description);
				}
			}
		} else {
			$this->sendTextMsg($openid, '该红包不是本活动使用范围');
			return false;
		}
	}
	
	/***商品券***/
	public function productvoucher($con, $openid, $UserID, $voucherID)
	{
		//根据商品ID查看商品券
		$productSql = "SELECT lID,dEndDate,statusID,lProductBatch FROM `ProductVoucher` WHERE sProductVoucherID = '" . $voucherID . "'";
		$result = $con->query($productSql);
		if ($result) {
			$voucher = $result->fetch_row();
			//领取商品券记录
			$RpvSql = "SELECT luserID,statusID,lID FROM ReceiveProductVoucher WHERE sProductVoucherID  = '" . $voucherID . "'";
			$resultRpv = $con->query($RpvSql);
			$Rpvoucher = $resultRpv->fetch_row();
			$endDate = strtotime($voucher[1]);     //红包结束之间
			$difTime = time() - $endDate;                //当前时间-红包时间的差值
			
			//状态为2或者当前时间大于商品有效结束时间，说明商品券已失效
			if ($voucher[2] == 2 || $difTime > 0) {
				$this->sendTextMsg($openid, '该商品券已失效');
				return false;
			}
			//已领取
			if (!empty($Rpvoucher)) {
				if ($UserID == $Rpvoucher[0]) {
					if ($Rpvoucher[1] == 0) {
						//推送
						$title = '该商品券您未领取';       //标题
						$description = '';     //描述
						$url = 'http://' . $_SERVER['HTTP_HOST'] . '/lsj/site/voucher?category=productVoucher&randID=' . $voucherID . '&UserID' . $UserID;                                                                      //图片跳转链接
						$imgUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/images/redpacket/ticket.jpg';  //推送界面的图片
						$this->sendMsg($openid, $url, $imgUrl, $title, $description);
					} else {
						//推送
						$title = '该红包已领取';       //标题
						$description = '';     //描述
						$url = 'http://' . $_SERVER['HTTP_HOST'] . '/lsj/site/productvouchersuccess?lID=' . $Rpvoucher[2];
						//图片跳转链接
						$imgUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/images/redpacket/ticket.jpg';  //推送界面的图片
						
						$this->sendMsg($openid, $url, $imgUrl, $title, $description);
					}
					
				} else {
					$this->sendTextMsg($openid, '该商品券已被别人领走了');
					return false;
				}
			} else {
				//创建领取商品记录
				$NowTime = gmdate('Y-m-d H:i:s', time() + 8 * 3600);
				$addSql = "INSERT INTO ReceiveProductVoucher (`OwnerID`,`EditUserID`,`dNewDate`,`luserID`,`statusID`,`sProductVoucherID`,`sProductVoucherName`,`lProductBatch`)
VALUES('5','" . $UserID . "','" . $NowTime . "','" . $UserID . "','0','" . $voucherID . "','" . $voucher[0] . "','" . $voucher[3] . "')";
				if ($con->query($addSql)) {
					//推送
					$title = '您有商品券，尽快领取哦';       //标题
					$description = '有商品券了，买买买';     //描述
					$url = 'http://' . $_SERVER['HTTP_HOST'] . '/lsj/site/voucher?category=productVoucher&randID=' . $voucherID . '&UserID' . $UserID;                                                                      //图片跳转链接
					$imgUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/images/redpacket/ticket.jpg';  //推送界面的图片
					
					$this->sendMsg($openid, $url, $imgUrl, $title, $description);
				}
				
			}
		} else {
			$this->sendTextMsg($openid, '该商品券不是本活动使用范围');
			return false;
		}
	}
	
	/***营销红包***/
	public function agentRedpacket($con, $openid, $UserID, $redID, $NewAgentID = null)
	{
		//查询红包相关信息
		$sql = "SELECT endDate,startDate,fBalance,surplusbagCount,remark,userID,bagCount,bAllowAgent from redbag where lID= '" . $redID . "'
        and statusID=1 ";
		$result = $con->query($sql);
		if ($result) {
			$redpacket = $result->fetch_row();
			$endDate = strtotime($redpacket[0]);   //红包结束之间
			$difTime = time() - $endDate;      //当前时间-红包时间的差值
			//状态为2获取当前时间大于结束时间，说明该红包已失效
			if ($difTime > 0) {
				$title = '该红包已超过24小时';       //标题
				$description = '点击查看抢红包记录';     //描述
				$url = 'http://' . $_SERVER['HTTP_HOST'] . '/lsj/agentredbag/redbagreceiveuser?redBagID=' . $redID;                                                //图片跳转链接
				$imgUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/images/new4/redpackage_14.png'; //推送界面的图片
				$this->sendMsg($openid, $url, $imgUrl, $title, $description);
				return false;
			}
			
			/* 判断该红包是否允许经销商领取 panlong 2019-1-18 11:29:33 开始 */
			if ($redpacket[7] == -1) {
				//如果不允许经销商领取，判断用户是否是经销商
				if ($NewAgentID) {
					$title = '这个是买家红包，你不能领取哦~';       //标题
					$description = '这个红包是普通买家红包，你不能领取哦~';     //描述
					$url = 'http://' . $_SERVER['HTTP_HOST'] . '/lsj/agentredbag/redbagreceiveuser?redBagID=' . $redID;                                                //图片跳转链接
					$imgUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/images/new4/redpackage_14.png'; //推送界面的图片
					$this->sendMsg($openid, $url, $imgUrl, $title, $description);
					return false;
				}
			}
			
			/* 判断该红包是否允许经销商领取 panlong 2019-1-18 11:29:33 开始 */
			
			
			//查询红包是谁领取的
			$redSql = "SELECT lID FROM `ReceiveRedPacket`  where UserID=$UserID and redType=1  and sRedPacketName=$redID";
			$ReceiveRed = $con->query($redSql);
			if ($ReceiveRed) {
				$ReceiveRed = $ReceiveRed->fetch_row();
			} else {
				$ReceiveRed = '';
			}
			//判断扫描的人是不是领走该红包的人，是的话再发一遍图文消息，否则执行else语句
			if (!empty($ReceiveRed)) {
				$title = '您已领取该红包';       //标题
				$description = '红包有效期为一个月，请及时使用';     //描述
				$url = 'http://' . $_SERVER['HTTP_HOST'] . '/lsj/agentredbag/redbagreceiveuser?redBagID=' . $redID;                                                //图片跳转链接
				$imgUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/images/new4/redpackage_14.png'; //推送界面的图片
				$this->sendMsg($openid, $url, $imgUrl, $title, $description);
				return false;
			} else {
				//判断是否还有红包
				if ($redpacket[3] <= 0) {
					$title = '纳尼，手又慢了';       //标题
					$description = '红包已抢光哦。祝亲19年好年又好运！';//描述
					//$url = 'http://' . $_SERVER['HTTP_HOST'];//图片跳转链接
					$url = 'http://' . $_SERVER['HTTP_HOST'] . '/lsj/agentredbag/redbagreceiveuser?redBagID=' . $redID.'&finished=1';//图片跳转链接
					$imgUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/images/new4/redpackage_14.png'; //推送界面的图片
					$this->sendMsg($openid, $url, $imgUrl, $title, $description);
					return false;
				}
				
				$endDate = date('Y-m-d H:i:s', strtotime($redpacket[0]) + 30 * 24 * 3600);//默认有效期一天 ;
				$startDate = $redpacket[1];
				$fBalance = $redpacket[2];
				$surplusbagCount = $redpacket[3];
				$remark = $redpacket[4];
				//随机红包金额
				$fBalance = $fBalance * 100;
				if ($surplusbagCount == 1) {
					$fMoney = $fBalance;
				} else {
					$fMoney = mt_rand(1, ($fBalance - $surplusbagCount + 1) * 2 / $surplusbagCount);
				}
				$fMoney = $fMoney / 100;
				
				//判断红包领取表是否未创建，未创建则进去创建，已创建则跳过
				$NowTime = gmdate('Y-m-d H:i:s', time() + 8 * 3600);
				$addSql = "INSERT INTO `ReceiveRedPacket` (`OwnerID`,`EditUserID`,`dNewDate`,`UserID`,`statusID`,`sRedPacketName`,`fMoney`,`startDate`,`endDate`,`redType`,`sName`,`fBalance`)
VALUES('5','" . $UserID . "','" . $NowTime . "','" . $UserID . "','1','" . $redID . "','" . $fMoney . "','" . $startDate . "','" . $endDate . "',1,'" . $remark . "','" . $fMoney . "')";
				if ($con->query($addSql)) {
					$fBalance = $fBalance / 100 - $fMoney;
					$surplusbagCount -= 1;
					$updateSQL = "update redbag set fBalance=$fBalance,surplusbagCount=$surplusbagCount where  lID=$redID";
					if ($con->query($updateSQL)) {
						//推送
						$title = '您有红包，快快领取哦';       //标题
						$description = '红包来了，抢抢抢';     //描述
						$url = 'http://' . $_SERVER['HTTP_HOST'] . '/lsj/agentredbag/redbagreceive?redBagID=' . $redID;//图片跳转链接
						$imgUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/images/new4/redpackage_14.png';  //推送界面的图片
						$this->sendMsg($openid, $url, $imgUrl, $title, $description);
						
						/* 判断是否发放奖励红包 panlong 2019-1-10 14:49:18 开始 */
						$sRewardsql = "SELECT SysUserID,TypeID,lBaseNum,fRemainMoney,lRemainNum from rewardredbaginfo where RedBagID= '" . $redID . "' and lRemainNum > 0 ";
						$result = $con->query($sRewardsql);
						if ($result) {
							$arrRewardInfo = $result->fetch_row();
							$SysUserID = $arrRewardInfo[0];
							$TypeID = $arrRewardInfo[1];
							$lBaseNum = $arrRewardInfo[2];
							$fRemainMoney = $arrRewardInfo[3];
							$lRemainNum = $arrRewardInfo[4];
							
							//奖励红包未发完且领取人不是发放人自己才能发放奖励红包
							if ($lRemainNum > 0 && $SysUserID != $UserID) {
								$lReceiveNum = $redpacket[6] - $surplusbagCount;
								$dStartDate = gmdate('Y-m-d', time() + 8 * 3600);
								
								/* 当前用户如果已触发奖励3次，则结束（上面的新增记录事务尚未提交，下面的循环处理查询不到数据）panlong 2019-1-16 11:37:25 开始 */
								$sThreeSql = "SELECT COUNT(lID) lNum from RewardRedBagLog where SysUserID = " . $SysUserID . " and ReceiveUserID = " . $UserID . " and dNewDate > " . $dStartDate;
								$result = $con->query($sThreeSql);
								if ($result) {
									$arrUserID = $result->fetch_row();
									//如果有人已触发奖励3次，则计数减一
									if ($arrUserID[0] >= 3) {
										return false;
									}
								}
								/* 当前用户如果已触发奖励3次，则结束（上面的新增记录事务尚未提交，下面的循环处理查询不到数据）panlong 2019-1-16 11:37:25 结束 */
								
								//如果发放人自己也领取了，计数减一
								$sAgentSql = "select lID from ReceiveRedPacket where sRedPacketName=$redID and UserID=$SysUserID";
								$result = $con->query($sAgentSql);
								if ($result->fetch_row()) {
									$lReceiveNum--;
								}
								
								/* 判断已领取用户中是否有已触发奖励达到3次的 panlong 2019-1-15 16:18:24 开始 */
								$sAllUserSql = "SELECT UserID FROM ReceiveRedPacket where sRedPacketName= " . $redID;
								$result = $con->query($sAllUserSql);
								while ($value = $result->fetch_row()) {
									$sThreeSql = "SELECT COUNT(lID) lNum from RewardRedBagLog where SysUserID = " . $SysUserID . " and ReceiveUserID = " . $value[0] . " and dNewDate > " . $dStartDate;
									$res = $con->query($sThreeSql);
									if ($res) {
										$arrUserID = $res->fetch_row();
										//如果有人已触发奖励3次，则计数减一
										if ($arrUserID[0] >= 3) {
											$lReceiveNum--;
										}
									}
								}
								/* 判断已领取用户中是否有已触发奖励达到3次的 panlong 2019-1-15 16:18:24 结束 */
								
								//根据奖励红包类型判断当前奖励红包金额
								if ($TypeID == 1) {
									//当前领取数必须为系统基数的倍数才能发放奖励红包（需扣除发放人自己领取）
									if ($lReceiveNum && $lReceiveNum % $lBaseNum == 0) {
										if ($lRemainNum == 1) {
											//单个奖励红包金额不得超过6元
											$fRewardMoney = $fRemainMoney > 6 ? 6 : $fRemainMoney;
										} else {
											$fRewardMoney = mt_rand(1, ($fRemainMoney * 100) * 2 / $lRemainNum);
											$fRewardMoney /= 100;
										}
									} else {
										return false;
									}
								} else {
									$fRewardMoney = $fRemainMoney;
								}
								
								$fRemainMoney -= $fRewardMoney;
								$lRemainNum--;
								
								//更新奖励红包信息
								$updateSQL = "update rewardredbaginfo set fRemainMoney=$fRemainMoney,lRemainNum=$lRemainNum where RedBagID=$redID";
								if ($con->query($updateSQL)) {
									//发放奖励红包
									$addSql = "INSERT INTO `ReceiveRedPacket` (`OwnerID`,`EditUserID`,`dNewDate`,`UserID`,`statusID`,`fMoney`,`startDate`,`endDate`,`redType`,`sName`,`fBalance`)
VALUES('5','" . $SysUserID . "','" . $NowTime . "','" . $SysUserID . "','1','" . $fRewardMoney . "','" . $startDate . "','" . $endDate . "',3,'手气红包','" . $fRewardMoney . "')";
									if ($con->query($addSql)) {
										$RewardID = $con->insert_id;
										
										//查询奖励人openid
										$sqlUserInfo = "SELECT sOpenID FROM wechat WHERE SysUserID=" . $SysUserID;
										$resultUser = $con->query($sqlUserInfo);
										if ($resultUser) {
											$user = $resultUser->fetch_row();
											
											//生成奖励发放记录
											$addSql = "INSERT INTO `RewardRedBagLog` (`SysUserID`,`RedBagID`,`ReceiveUserID`,`fMoney`,`dNewDate`)
VALUES(" . $SysUserID . "," . $redID . "," . $UserID . ",'" . $fRewardMoney . "','" . $NowTime . "')";
											if ($con->query($addSql) && $fRewardMoney > 0) {
												//推送
												$title = '获得1个手气红包，快领取';       //标题
												$description = '发越多，赚越多。你发的越多，就能获得越多手气红包！';     //描述
												$url = 'http://' . $_SERVER['HTTP_HOST'] . '/lsj/agentredbag/redbagreceive?redBagID=' . $redID . '&bReward=1&RewardID=' . $RewardID;//图片跳转链接
												$imgUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/images/new4/redpackage_14.png';  //推送界面的图片
												$this->sendMsg($user[0], $url, $imgUrl, $title, $description);
											}
										}
									}
								}
							}
						}
						
						/* 判断是否发放奖励红包 panlong 2019-1-10 14:49:18 结束 */
					}
				}
			}
		} else {
			$this->sendTextMsg($openid, '该红包不在本活动使用范围');
			return false;
		}
	}
	
	public function lsjRedpacket($con, $openid, $UserID, $redID, $description, $endMsg)
	{
		//查询红包相关信息
		$sql = "SELECT endDate,startDate,fBalance,surplusbagCount,remark,redBagType from redbag where lID= '" . $redID . "'
        and statusID=1 ";
		$result = $con->query($sql);
		if ($result) {
			$redpacket = $result->fetch_row();
			$endDate = strtotime($redpacket[0]);   //红包结束之间
			$difTime = time() - $endDate;      //当前时间-红包时间的差值
			//状态为2获取当前时间大于结束时间，说明该红包已失效
			if ($difTime > 0) {
				$title = '红包已经抢光';       //标题
				$url = 'http://' . $_SERVER['HTTP_HOST'] . '/lsj/agentredbag/redbagreceiveuserlsj?redBagID=' . $redID;                                                //图片跳转链接
				$imgUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/images/new4/redpackage_14.png'; //推送界面的图片
				$this->sendMsg($openid, $url, $imgUrl, $title, $description);
				return false;
			}
			//查询红包是谁领取的
			$redSql = "SELECT lID FROM `ReceiveRedPacket`  where UserID=$UserID and redType=1  and sRedPacketName=$redID";
			$ReceiveRed = $con->query($redSql);
			if ($ReceiveRed) {
				$ReceiveRed = $ReceiveRed->fetch_row();
			} else {
				$ReceiveRed = '';
			}
			//判断扫描的人是不是领走该红包的人，是的话再发一遍图文消息，否则执行else语句
			$imgUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/images/new4/redpackage_14.png';
			$url = 'http://' . $_SERVER['HTTP_HOST'] . '/lsj/agentredbag/redbagreceivelsj?redBagID=' . $redID;                                                //图片跳转链接
			if (!empty($ReceiveRed)) {
				$title = '您已领取该红包';       //标题
				$url = 'http://' . $_SERVER['HTTP_HOST'] . '/lsj/agentredbag/redbagreceiveuserlsj?redBagID=' . $redID;                                                //图片跳转链接
				$this->sendMsg($openid, $url, $imgUrl, $title, $description);
				return false;
			} else {
				//判断是否还有红包
				if ($redpacket[3] <= 0) {
					$title = '纳尼，手又慢了';       //标题
					$this->sendMsg($openid, $url, $imgUrl, $title, $endMsg);
					return false;
				}
				
				$endDate = date('Y-m-d H:i:s', strtotime($redpacket[0]) + 30 * 24 * 3600);//默认有效期一天 ;
				$startDate = $redpacket[1];
				$fBalance = $redpacket[2];
				$surplusbagCount = $redpacket[3];
				$remark = $redpacket[4];
				//随机红包金额
				$fBalance = $fBalance * 100;
				if ($surplusbagCount == 1) {
					$fMoney = $fBalance;
				} else {
					$fMoney = mt_rand(1, ($fBalance - $surplusbagCount + 1) * 2 / $surplusbagCount);
				}
				$fMoney = $fMoney / 100;
				
				//判断红包领取表是否未创建，未创建则进去创建，已创建则跳过
				$NowTime = gmdate('Y-m-d H:i:s', time() + 8 * 3600);
				$addSql = "INSERT INTO `ReceiveRedPacket` (`OwnerID`,`EditUserID`,`dNewDate`,`UserID`,`statusID`,`sRedPacketName`,`fMoney`,`startDate`,`endDate`,`redType`,`sName`,`fBalance`)
VALUES('5','" . $UserID . "','" . $NowTime . "','" . $UserID . "','1','" . $redID . "','" . $fMoney . "','" . $startDate . "','" . $endDate . "',1,'" . $remark . "','" . $fMoney . "')";
				if ($con->query($addSql)) {
					$fBalance = $fBalance / 100 - $fMoney;
					$surplusbagCount -= 1;
					$updateSQL = "update redbag set fBalance=$fBalance,surplusbagCount=$surplusbagCount where  lID=$redID";
					if ($con->query($updateSQL)) {
						//推送
						$title = '您有红包，快快领取哦';       //标题
						$this->sendMsg($openid, $url, $imgUrl, $title, $description);
					}
				}
			}
		} else {
			$this->sendTextMsg($openid, '该红包不在本活动使用范围');
			return false;
		}
	}
	
	//响应事件消息
	public function responseEventMsg($postObj)
	{
		$content = "";
		if ($postObj->Event == "subscribe") {
//            $content = "终于等到你，来三斤就对啦！\r\n";
//            $content .= "精选农特产，传递家乡味\r\n";
//            $content .= "圆你我小时候的味道~\r\n";
//            $content .= "点击菜单栏进入商城\r\n";
//            $content .= "轻松开启购物模式";
//			$description = '欢迎来到来三斤商城~
//这里汇集了全国各地健康原生态农特产品
//绿色、健康、原产地直供
//相约来三斤，共寻家乡味
//点击左下角【进入商城】立即开启健康美食之旅~
//如您在购买过程遇到什么问题，可点击右下角的【在线客服】咨询哦';
			$content = "HI,终于等到你~\r\n";
			$content .= "你是我的小吃货吗\r\n";
			$content .= "9.9撸好货带我回家过年啦\r\n";
			$content .= "<a href='http://m.laisanjin.cn/lsj/communitybuy/index'>点击链接立马扫货</a>\r\n";
			$content .= "\r\n";
			$content .= "你的助农账单已生成\r\n";
			$content .= "据说只有5%的人是美食风向标哦，哇咔咔咔咔~\r\n";
			$content .= "<a href='http://m.laisanjin.cn/lsj/activity/annual'>点击立即获取</a>\r\n";
			$this->replayMsg($postObj, $content);
		} else if ($postObj->Event == "CLICK") {
			//触发菜单点击事件
			$key = $postObj->EventKey;
			if ($key == "lineServer") {
				echo $resultStr = $this->handleText($postObj);
			} else if ($key == "lineServerClick") {
				$openid = $postObj->FromUserName;
				$contentNews = "小主，在公众号聊天框输入关键词
如“售后”即可得到你想要的
若需人工服务，
请输入【转人工】即可咨询。

人工客服时间：
周一至周五
8:40-12:00 14:00-18:00
节假日值班
上午9:00-12:00
下午13:00-17:00

若已过下班时间，请点击<a href='http://cn.mikecrm.com/DlASzX8'>“留言”</a>
我们将于上班时间为您处理，谢谢。";
				$this->sendTextMsg($openid, $contentNews);
				
			}
		} else {
			$parameters = $postObj->EventKey;
			$paramList = explode(',', $parameters);
			$openid = $postObj->FromUserName;
			if ($paramList[0] == 'redbag') {
				$this->sendMsg($openid);
			}
		}
	}
	
	//菜单点击事件
	public function handleText($postObj)
	{
		$record[0] = array(
			'title' => '售后指南',
			'description' => '',
			'picUrl' => 'https://mmbiz.qlogo.cn/mmbiz_jpg/ZlHaibvk7VQKUrOH0lQxgJJ54ibuOgvPTLCiahqUUiaNSdO7AeUxibnCtTuwnDdiaOXIpzZwzOIIU3AcgI4UP2lsyiaaw/0?wx_fmt=jpeg',
			'url' => 'http://mp.weixin.qq.com/s/WWlQjpl8esyWIsSPK7Xd1w'
		);
		$record[1] = array(
			'title' => '理赔标准',
			'description' => '',
			'picUrl' => 'https://mmbiz.qlogo.cn/mmbiz_jpg/ZlHaibvk7VQKUrOH0lQxgJJ54ibuOgvPTLn4ib8Z9XicYv0OBunTWXDxybNXORXyNqrRdtEMoKl3hlPL5o2e46Q7Jw/0?wx_fmt=jpeg',
			'url' => 'http://mp.weixin.qq.com/s/K8I_0jT5_153fvQmMwWxRA'
		);
		$record[2] = array(
			'title' => '客户退款申请流程',
			'description' => '',
			'picUrl' => 'https://mmbiz.qlogo.cn/mmbiz_jpg/ZlHaibvk7VQKUrOH0lQxgJJ54ibuOgvPTLlrRKPCod7K6HlichPKV5dJVRkWp2zRYPOo4wN9DJRcNoGfnlKPqdicGg/0?wx_fmt=jpeg',
			'url' => 'http://mp.weixin.qq.com/s/4vq4wwuqHwfSDK6-XZq_Ow'
		);
		$record[3] = array(
			'title' => '经销商代理退款申请流程',
			'description' => '',
			'picUrl' => 'https://mmbiz.qlogo.cn/mmbiz_jpg/ZlHaibvk7VQKUrOH0lQxgJJ54ibuOgvPTLlyAdDcVTPraYwpq33ic28OM5Zuvx0QzS9hLKFR6DpZAleWa4Naj3g8Q/0?wx_fmt=jpeg',
			'url' => 'http://mp.weixin.qq.com/s/SGt8-0P9nTeb4aJnF0C8ww'
		);
		$record[4] = array(
			'title' => '如何查询订单的物流信息',
			'description' => '',
			'picUrl' => 'https://mmbiz.qlogo.cn/mmbiz_jpg/ZlHaibvk7VQKUrOH0lQxgJJ54ibuOgvPTLCsXgA0upKnwibKicG1gMKZttbGaOFvZkURm9yHhXmngcib1ZT3knAViaew/0?wx_fmt=jpeg',
			'url' => 'http://mp.weixin.qq.com/s/-eVrWzihCdBFoQhzdcPGUQ'
		);
		
		$resultStr = $this->response_multiNews($postObj, $record);
		echo $resultStr;
	}
	
	/**
	 * 发送文本消息
	 * @param $touser 收件人
	 * @param $content 消息内容
	 * @return bool
	 */
	public function sendTextMsg($touser, $content)
	{
		$access_token = $this->getAccessToken();
		$postdata = '{"touser":"' . $touser . '","msgtype":"text","text":{"content":"' . $content . '"}}';
		$opts = array(
			'http' => array(
				'method' => 'POST',
				'Content-Length' => strlen($postdata),
				'Host' => 'api.weixin.qq.com',
				'Content-Type' => 'application/json',
				'content' => $postdata
			)
		);
		$context = stream_context_create($opts);
		$result = file_get_contents("https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=$access_token", true, $context);
		return true;
	}
	
	public function sendRegTemplateMsg($data = [], $openId, $url, $TemplateID = 'WHw2EFwPLsPbnZz-THBje4mqEjsmkenqt1MgvpzBoJk')
	{
		$postUrl = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $this->getAccessToken();
		
		$postData = array(
			'touser' => $openId, // openid是发送消息的基础
			'template_id' => $TemplateID, // 模板id
			'url' => $url, // 点击跳转地址
			'topcolor' => '#FF0000', // 顶部颜色
			'data' => $data
		);
		$postData = urldecode(json_encode($postData));
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $postUrl);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		$return = curl_exec($ch);
		curl_close($ch);
		return true;
	}
	
	
	//回复文本消息
	public function replayMsg($postObj, $content)
	{
		//获取发送者
		$fromUsername = $postObj->FromUserName;
		//获取接收者
		$toUsername = $postObj->ToUserName;
		//获取消息发送的时间戳
		$time = time();
		//xml格式：我们要回复的内容
		$textTpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            <FuncFlag>0</FuncFlag>
                            </xml>";
		$msgType = "text";
		$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $content);
		echo $resultStr;
	}
	
	/**
	 * 推送图文消息
	 * @param $title $openID $url $imgUrl $description
	 * @return bool|string
	 * @author ldz
	 * @Time 2017-11-21 11:09:29
	 */
	private function sendMsg($openID, $url, $imgUrl, $title, $description)
	{
		$access_token = $this->getAccessToken();
		$reText = '{
          "touser":"' . $openID . '",
          "msgtype":"news",
          "news":{
          "articles": [
                 {
                  "title":"' . $title . '",
                     "description":"' . $description . '",
                     "url":"' . $url . '",
                     "picurl":"' . $imgUrl . '"
                 }
                 ]
            }
        }';
		$opts = array(
			'http' => array(
				'method' => 'POST',
				'Content-Length' => strlen($reText),
				'Host' => 'api.weixin.qq.com',
				'Content-Type' => 'application/json',
				'content' => $reText
			)
		);
		$context = stream_context_create($opts);
		$result = file_get_contents("https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=$access_token", true, $context);
		return $result;
	}
	
	/**
	 * 菜单点击事件，文本消息
	 * @param $object
	 * @param $newsContent
	 * @return string
	 */
	private function response_multiNews($object, $newsContent)
	{
		$newsTplHead = "<xml>
				    <ToUserName><![CDATA[%s]]></ToUserName>
				    <FromUserName><![CDATA[%s]]></FromUserName>
				    <CreateTime>%s</CreateTime>
				    <MsgType><![CDATA[news]]></MsgType>
				    <ArticleCount>%s</ArticleCount>
				    <Articles>";
		$newsTplBody = "<item>
				    <Title><![CDATA[%s]]></Title> 
				    <Description><![CDATA[%s]]></Description>
				    <PicUrl><![CDATA[%s]]></PicUrl>
				    <Url><![CDATA[%s]]></Url>
				    </item>";
		$newsTplFoot = "</Articles>
					<FuncFlag>0</FuncFlag>
				    </xml>";
		
		$bodyCount = count($newsContent);
		$bodyCount = $bodyCount < 10 ? $bodyCount : 10;
		
		$header = sprintf($newsTplHead, $object->FromUserName, $object->ToUserName, time(), $bodyCount);
		$body = '';
		foreach ($newsContent as $key => $value) {
			$body .= sprintf($newsTplBody, $value['title'], $value['description'], $value['picUrl'], $value['url']);
		}
		
		$FuncFlag = 0;
		$footer = sprintf($newsTplFoot, $FuncFlag);
		
		return $header . $body . $footer;
	}
	
	/**
	 * 获取用户信息
	 * @param $openID
	 * @return mixed
	 * @author ldz
	 * @Time 2017-11-21 11:18:34
	 */
	public function getUserinfo($openID)
	{
		$access_token = $this->getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openID";
		$authInfo = $this->geturl($url);
		$userInfo = json_decode($authInfo, true);
		return $userInfo;
	}
	
	/**
	 * curl解析
	 * @param $url
	 * @return mixed
	 * @author ldz
	 * @Time 2017-11-21 11:19:22
	 */
	public function geturl($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}
	
	/**
	 * 创建SysUser 随机ID
	 * @return bool|mixed|string
	 * @author ldz
	 * @Time 2017-11-21 16:14:59
	 */
	public static function SysUserID()
	{
		$mt = explode(" ", microtime());
		$sOid = $mt [1] . "" . $mt [0] . "" . mt_rand() . "" . mt_rand();
		$sOid = str_replace('.', '0', $sOid);
		
		if (strlen($sOid) < 32) {
			for ($i = strlen($sOid) + 1; $i <= 32; $i++) {
				$sOid .= "0";
			}
		} else if (strlen($sOid) > 32) {
			$sOid = substr($sOid, 0, 32);
		}
		
		return $sOid;
	}
	
	/**
	 * 博饼券扫码操作
	 * @author 张正帝  <919059960@qq.com>
	 * @time 2018年09月03日
	 */
	public function Pancakes($DatabaseConnect, $PancakesID, $SysUserID, $OpenID)
	{
		//图文消息
		$imgUrl = "http://m.laisanjin.cn/images/new4/mid_autumn1.jpg";
		$description = '快快点击领取金币，开启健康美食之旅吧！';
		
		//查询博饼券记录
		$sqlSelectPancakes = "SELECT `Status`,`fMoney`,`sysUserID`
                              FROM pancakes 
                              WHERE lID = " . $PancakesID;
		$result = $DatabaseConnect->query($sqlSelectPancakes);
		$PancakesInfo = $result->fetch_row();
		
		//查询用户金币数额
		$sqlSelectSysUser = "SELECT `fGold`
                              FROM sysuser 
                              WHERE lID = " . $SysUserID;
		$result = $DatabaseConnect->query($sqlSelectSysUser);
		$SysUserInfo = $result->fetch_row();
		
		//判断博饼券是否已被领取
		if ($PancakesInfo[0] != 1) {
			$url = 'http://' . $_SERVER["HTTP_HOST"] . '/lsj/member/mygold';
			$title = '这张博饼券已被领取，换一张吧！';
			$this->sendMsg($OpenID, $url, $imgUrl, $title, $description);
			return false;
		}
		
		//更新博饼券记录
		$sqlUpdatePancakes = "UPDATE pancakes
                              SET `receiverID` = " . $SysUserID . ",`Status` = 2 
                              WHERE lID = " . $PancakesID;
		$DatabaseConnect->query($sqlUpdatePancakes);
		
		//扫码用户赠送金币
		$fMoney = (int)$PancakesInfo[1] + (int)$SysUserInfo[0];
		$sqlUpdateSysUser = "UPDATE sysuser
                             SET `fGold` = " . $fMoney . " 
                             WHERE lID = " . $SysUserID;
		$DatabaseConnect->query($sqlUpdateSysUser);
		
		//新增金币流水记录
		$time = gmdate('Y-m-d H:i:s', time() + 8 * 3600);
		$sqlInsertGoldFlow = "INSERT INTO goldflow (
                                    `sName`,
                                    `OwnerID`,
                                    `NewUserID`,
                                    `EditUserID`,
                                    `dNewDate`,
                                    `dEditDate`,
                                    `fChange`,
                                    `fBefore`,
                                    `fAfter`,
                                    `SysUserID`,
                                    `sObjectName`,
                                    `ObjectID`,
                                    `TypeID`,
                                    `sLogoPath`,
                                    `sDescription`
                              ) 
                              VALUE (
                                    '博饼券赠送金币',
                                    '2',
                                    '2',
                                    '2',
                                    '" . $time . "',
                                    '" . $time . "',
                                    " . $PancakesInfo[1] . ",
                                    " . $SysUserInfo[0] . ",
                                    " . $fMoney . ",
                                    " . $SysUserID . ",
                                    'Pancakes',
                                    " . $PancakesID . ",
                                    'goldflow_type_exchange',
                                    '/images/goldflow_update.jpg',
                                    '博饼券赠送金币'
                              )";
		$DatabaseConnect->query($sqlInsertGoldFlow);
		
		$url = 'http://' . $_SERVER["HTTP_HOST"] . '/lsj/member/mygold?fMoney=' . $PancakesInfo[1] . "&sysUserID=" . $PancakesInfo[2];
		$title = '今年中秋不一样，我要我的原生态好味道。';
		$this->sendMsg($OpenID, $url, $imgUrl, $title, $description);
	}
	
	/** 营销券扫码
	 * @author cgq
	 * @time 2018年11月12日 16:24:33
	 */
	public function marketingvoucher($DatabaseConnect, $marketingvoucherID, $userID, $OpenID)
	{
		//消息
		$description = '快快点击领取礼品券，开启健康美食之旅吧！';
		
		//判断营销券是否已经被领取
		$sqlSelectPancakes = "SELECT UserID,fMoney,fFullReduction
                              FROM MarketingVoucher
                              WHERE lID = " . $marketingvoucherID;
		$result = $DatabaseConnect->query($sqlSelectPancakes);
		
		$PancakesInfo = $result->fetch_row();
		$url = 'http://' . $_SERVER["HTTP_HOST"] . '/lsj/marketingvoucher/myvoucher';
		
		//已经被领取
		if ($PancakesInfo[0] != "") {
			$title = '这张礼品券已被领取，换一张吧！';
			$description = '礼品券已经被领取！';
			if ($PancakesInfo[2] == "") {
				//不是满减
				$imgUrl = "http://m.laisanjin.cn/images/new4/fullreduction.jpg";
			} else {
				//是满减
				$imgUrl = "http://m.laisanjin.cn/images/new4/reach.jpg";
			}
			$this->sendMsg($OpenID, $url, $imgUrl, $title, $description);
			return false;
		}
		
		if ($PancakesInfo[2] == "") {
			//不是满减
			$imgUrl = "http://m.laisanjin.cn/images/new4/fullreduction.jpg";
			$title = '恭喜获得' . $PancakesInfo[1] . '元礼包券';
		} else {
			//是满减
			$imgUrl = "http://m.laisanjin.cn/images/new4/reach.jpg";
			$title = '恭喜获得满' . $PancakesInfo[2] . "减" . $PancakesInfo[1] . '礼品券';
		}
		
		
		//更新领取人
		$sqlUpdatePancakes = "UPDATE MarketingVoucher
                              SET `UserID` = " . $userID . "
                              WHERE lID = " . $marketingvoucherID;
		$DatabaseConnect->query($sqlUpdatePancakes);
		
		$this->sendMsg($OpenID, $url, $imgUrl, $title, $description);
	}
	
	/** 新零售 代理
	 * @author zy
	 * @time 2018年12月11日 09:09:45
	 * $con 数据链接对象
	 * $openid 接信息openID
	 * $sysUserID 当前用户ID
	 * $inviteID 邀请人ID
	 * $ProductID 商品ID
	 */
	public function newRetail($con, $openid, $sysUserID, $inviteID, $ProductID)
	{
		//判断是否是新零售代理
		$sqlSelectPancakes = "SELECT SysUserID  FROM wxshop  WHERE bSpecial=1 and  SysUserID = " . $sysUserID;
		$result = $con->query($sqlSelectPancakes);
		if ($result && $result->fetch_row()[0]) {
			$url = 'http://' . $_SERVER["HTTP_HOST"] . '/lsj';
			$imgUrl = "http://m.laisanjin.cn/images/new4/inviteOpenRetail.jpg";
			$title = '欢迎回来';
			$description = '点击进入您店铺';
			$this->sendMsg($openid, $url, $imgUrl, $title, $description);
		} else {
			$url = 'http://' . $_SERVER["HTTP_HOST"] . '/lsj/cart/checkout?retail=1&ProductID=' . $ProductID . '&FromUserID=' . $inviteID;
			$imgUrl = "http://m.laisanjin.cn/images/new4/inviteOpenRetail.jpg";
			$title = '来三斤邀您一起共创未来';
			$description = '点击开启您的新零售之旅';
			$this->sendMsg($openid, $url, $imgUrl, $title, $description);
		}
	}
}

?>
