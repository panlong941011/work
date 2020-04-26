<?php

namespace myerm\backend\common\libs;

use Yii;
use yii\helpers\Html;
use yii\web\Request;

class LinkPager extends \yii\widgets\LinkPager
{
	public $options = ['class' => 'pagination fn-clear'];
	public $firstPageLabel = "<<";
	public $lastPageLabel = ">>";
	public $nextPageLabel = '>';
	public $prevPageLabel = '<';
	public $maxButtonCount = 5;
	
	protected function getPageRange()
	{
		$currentPage = $this->pagination->getPage();
		$pageCount = $this->pagination->getPageCount();
	
		$beginPage = max(1, $currentPage - (int) ($this->maxButtonCount / 2));
		if (($endPage = $beginPage + $this->maxButtonCount - 1) >= $pageCount) {
			$endPage = $pageCount;
			$beginPage = max(1, $endPage - $this->maxButtonCount + 1);
		}
	
		return [$beginPage, $endPage];
	}
	
	protected function renderPageButtons()
	{
		$pageCount = $this->pagination->getPageCount();
		if ($pageCount < 2 && $this->hideOnSinglePage) {
			return '';
		}
	
		$buttons = [];
		$currentPage = $this->pagination->getPage();
	
		// first page
		if ($this->firstPageLabel !== false) {
			$buttons[] = $this->renderPageButton($this->firstPageLabel, 1, $this->firstPageCssClass, $currentPage <= 1, false);
		}
	
		// prev page
		if ($this->prevPageLabel !== false) {
			if (($page = $currentPage - 1) < 0) {
				$page = 0;
			}
			$buttons[] = $this->renderPageButton($this->prevPageLabel, $page, $this->prevPageCssClass, $currentPage <= 1, false);
		}
	
		// internal pages
		list($beginPage, $endPage) = $this->getPageRange();
		for ($i = $beginPage; $i <= $endPage; ++$i) {
			$buttons[] = $this->renderPageButton($i, $i, null, false, $i == $currentPage);
		}

		// next page
		if ($this->nextPageLabel !== false) {
			if (($page = $currentPage + 1) >= $pageCount - 1) {
				$page = $pageCount - 1;
			}
			$buttons[] = $this->renderPageButton($this->nextPageLabel, $currentPage+1, $this->nextPageCssClass, $currentPage >= $pageCount, false);
		}
	
		// last page
		if ($this->lastPageLabel !== false) {
			$buttons[] = $this->renderPageButton($this->lastPageLabel, $pageCount, $this->lastPageCssClass, $currentPage >= $pageCount, false);
		}
	
		return Html::tag('ul', implode("\n", $buttons), $this->options);
	}	
	
	
	protected function renderPageButton($label, $page, $class, $disabled, $active)
	{
		$options = ['class' => $class === '' ? null : $class];
		if ($active) {
			Html::addCssClass($options, $this->activePageCssClass);
		}
		if ($disabled) {
			Html::addCssClass($options, $this->disabledPageCssClass);
	
			return Html::tag('li', Html::tag('span', $label), $options);
		}
		$linkOptions = $this->linkOptions;
		$linkOptions['data-page'] = $page;
	
		if ($label == 1) {
			$request = Yii::$app->getRequest();
			$params = $request instanceof Request ? $request->getQueryParams() : [];
			
			
			if ($page > 0 || $page >= 0 && $this->pagination->forcePageParam) {
				$params[$this->pagination->pageParam] = $page;
			} else {
				unset($params[$this->pagination->pageParam]);
			}			
			
			
			
			$params[0] = Yii::$app->controller->getRoute();
			$urlManager = Yii::$app->getUrlManager();
			return Html::tag('li', Html::a($label, $urlManager->createUrl($params), $linkOptions), $options);
		} else {
			return Html::tag('li', Html::a($label, $this->pagination->createUrl($page), $linkOptions), $options);
		}	
	}	
	
}