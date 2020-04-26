<?php

/*
 * YII自带的分页，页码会多加1，因此覆盖
 */

namespace myerm\backend\common\libs;

use Yii;
use yii\web\Link;
use yii\web\Request;

class Pagination extends \yii\data\Pagination
{
	
    public $pageSizeLimit = [1, 100];
    
	public function getOffset()
	{
		$pageSize = $this->getPageSize();

		return $pageSize < 1 ? 0 : ($this->getPage() - 1) * $pageSize;
	}
	
	public function getLinks($absolute = false)
	{
		$currentPage = $this->getPage();
		$pageCount = $this->getPageCount();
		$links = [
		Link::REL_SELF => $this->createUrl($currentPage, null, $absolute),
		];
		if ($currentPage > 1) {
			$links[self::LINK_FIRST] = $this->createUrl(1, null, $absolute);
			$links[self::LINK_PREV] = $this->createUrl($currentPage - 1, null, $absolute);
		}
		if ($currentPage < $pageCount) {
			$links[self::LINK_NEXT] = $this->createUrl($currentPage + 1, null, $absolute);
			$links[self::LINK_LAST] = $this->createUrl($pageCount, null, $absolute);
		}
	
		return $links;
	}	
	
	
	public function createUrl($page, $pageSize = null, $absolute = false)
	{
		$page = (int) $page;
		$pageSize = (int) $pageSize;
		if (($params = $this->params) === null) {
			$request = Yii::$app->getRequest();
			$params = $request instanceof Request ? $request->getQueryParams() : [];
		}
		if ($page > 0 || $page >= 0 && $this->forcePageParam) {
			$params[$this->pageParam] = $page;
		} else {
			unset($params[$this->pageParam]);
		}
		if ($pageSize <= 0) {
			$pageSize = $this->getPageSize();
		}
		if ($pageSize != $this->defaultPageSize) {
			$params[$this->pageSizeParam] = $pageSize;
		} else {
			unset($params[$this->pageSizeParam]);
		}
		$params[0] = $this->route === null ? Yii::$app->controller->getRoute() : $this->route;
		$urlManager = $this->urlManager === null ? Yii::$app->getUrlManager() : $this->urlManager;
		if ($absolute) {
			return $urlManager->createAbsoluteUrl($params);
		} else {
			return $urlManager->createUrl($params);
		}
	}	
}