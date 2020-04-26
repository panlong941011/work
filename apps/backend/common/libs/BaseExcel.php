<?php


use yii;

include "../common/libs/PHPExcel.php";
include "../common/libs/PHPExcel/IOFactory.php";

/**
 * @author: bean
 * @time: 2016-12-19 10:08:18
 * @version 1.0
 */
class BaseExcel
{
    /**
     * @var \PHPExcel
     */
    protected $object;

    /**
     * header
     * @var array
     */
    protected $headers = [];

    /**
     * 标题
     * @var array
     */
    public $titles = [];

    /**
     * 数据内容
     * @var array
     */
    public $contents = [];

    /**
     * 导出的文件名
     * @var string
     */
    public $fileName;

    /**
     * 导入的文件
     */
    public $file;

    /**
     * 通用样式
     * @var array
     */
    public $commonStyle = [
        'fontSize' => 10,
        'fontName' => '微软雅黑'
    ];

    /**
     * 标题栏样式
     * @var array
     */
    public $titleStyle = [
        'bgColor' => '0070C0',
        'fontColor' => 'FFFFFFFF',
        'fontBold' => true,
        'textAlign' => 'center'
    ];

    /*
     * 导出
     */
    public function export()
    {
        $this->object = new \PHPExcel();

        $this->setHeader(); //配置头部信息
        $this->setTitle(); //填充标题栏
        $this->setTitleStyle(); //配置标题栏样式
        $this->setContent(); //填充数据栏
        $this->setCommonStyle(); //配置通用样式

        if (empty($this->fileName)) {
            $this->fileName = date('YmdHis', time());
        }

        ob_end_clean();
        $obj_Writer = \PHPExcel_IOFactory::createWriter($this->object, 'Excel5');

        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="' . $this->fileName . '.xls"');
        header("Content-Transfer-Encoding: binary");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $obj_Writer->save('php://output');
    }

    /**
     * 导入
     * @return array
     */
    public function import()
    {
        if (empty($this->file)) {
            return [];
        }

        $fileType = \PHPExcel_IOFactory::identify($this->file);
        $reader = \PHPExcel_IOFactory::createReader($fileType);
        $this->object = $reader->load($this->file);

        $data = [];
        foreach ($this->object->getAllSheets() as $sheet) {
            $sheetTitle = $sheet->getTitle();
            $sheetData = $sheet->toArray(null, true, true, false);
            $data[$sheetTitle] = $sheetData;
        }

        return $data;
    }

    /**
     * 配置标题栏
     * @return bool
     */
    protected function setTitle()
    {
        if (empty($this->titles)) {
            return false;
        }

        foreach ($this->titles as $rowKey => $titles) {
            foreach ($titles as $colKey => $title) {
                $this->object->getActiveSheet()->setCellValue($this->headers[$colKey] . ($rowKey + 1), $title);
            }
        }
        return true;
    }

    /**
     * 填充数据
     * @return bool
     */
    protected function setContent()
    {
        if (empty($this->contents)) {
            return false;
        }

        $titleRow = count($this->titles); //标题栏总行数

        foreach ($this->contents as $rowKey => $contents) {
            foreach ($contents as $colKey => $content) {
                $type = \PHPExcel_Cell_DataType::TYPE_STRING; //单元格数据类型
                $area = $this->headers[$colKey] . ($rowKey + 1 + $titleRow);
                $this->object->getActiveSheet()->setCellValueExplicit($area, $content, $type);
            }
        }
        return true;
    }

    /**
     * 标题栏样式
     * @return bool
     */
    protected function setTitleStyle()
    {
        $style = $this->titleStyle;

        if (empty($style)) {
            return false;
        }

        $row = count($this->titles); //总行数
        $col = count($this->titles[0]); //总列数
        $begain = $this->headers[0] . '1'; //开始坐标
        $end = $this->headers[$col - 1] . $row; //结束坐标
        $area = $begain . ':' . $end; //填充区域

        if (array_key_exists('bgColor', $style) && !empty($style['bgColor'])) {
            $this->object->getActiveSheet()->getStyle($area)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $this->object->getActiveSheet()->getStyle($area)->getFill()->getStartColor()->setRGB($style['bgColor']);
        }

        if (array_key_exists('fontColor', $style) && !empty($style['fontColor'])) {
            $this->object->getActiveSheet()->getStyle($area)->getFont()->getColor()->setARGB($style['fontColor']);
        }

        if (array_key_exists('fontBold', $style) && $style['fontBold']) {
            $this->object->getActiveSheet()->getStyle($area)->getFont()->setBold(true);
        }

        if (array_key_exists('textAlign', $style) && !empty($style['textAlign'])) {
            $this->object->getActiveSheet()->getStyle($area)->getAlignment()->setHorizontal($style['textAlign']);
        }

        return true;
    }

    /**
     * 通用样式
     * @return bool
     */
    protected function setCommonStyle()
    {
        $style = $this->commonStyle;

        if (empty($style)) {
            return false;
        }

        $row = count($this->titles) + count($this->contents); //总行数
        $col = count($this->titles[0]); //总列数
        $begain = $this->headers[0] . '1'; //开始坐标
        $end = $this->headers[$col - 1] . $row; //结束坐标
        $area = $begain . ':' . $end; //填充区域

        if (array_key_exists('fontSize', $style) && !empty($style['fontSize'])) {
            $this->object->getActiveSheet()->getStyle($area)->getFont()->setSize($style['fontSize']);
        }

        if (array_key_exists('fontName', $style) && !empty($style['fontName'])) {
            $this->object->getActiveSheet()->getStyle($area)->getFont()->setName($style['fontName']);
        }

        return true;
    }

    /**
     * 配置X轴header
     * @return bool
     */
    protected function setHeader()
    {
        $data = [];
        $letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',
            'U', 'V', 'W', 'X', 'Y', 'Z'];

        $count = count($this->titles[0]);

        if ($count <= 0) {
            return false;
        }

        for ($i = 0; $i < $count; $i++) {
            if ($i < 26) {
                $data[] = $letters[$i];
            } else {
                $key = (int)floor($i / 26);
                $data[] = $letters[$key - 1] . $letters[$i % 26];
            }
        }

        $this->headers = $data;
        return true;
    }

}