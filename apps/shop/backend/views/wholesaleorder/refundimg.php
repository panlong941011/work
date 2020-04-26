<style>
    .pic_wrap{
        position: relative;
         display: -webkit-box;
        display: -webkit-flex;
        display: flex;
        display: box;
        width: 600px;
     }
     .pic_list {
        display: -webkit-box;
        display: -webkit-flex;
        display: flex;
        display: box;
        position: relative;
    }
    .re_pic {
        width: 80px;
        height: 80px;
        border: 1px solid #ccc;
        position: relative;
        margin-right: 10px;
    }

    .pic_wrap .repic_top {
        position: absolute;
        height: 80px;
        width: 80px;
        left: 0;
        top: 0;
    }

    .pic_wrap .repic_top:after,
    .pic_wrap .repic_top:before {
        content: '';
        position: absolute;
        left: 50%;
        top: 50%;
        margin-left: -10px;
        margin-top: -1.5px;
        width: 20px;
        height: 3px;
        background: #d2d2d2;
    }

    .pic_wrap .repic_top:before {
        margin-left: -1.5px;
        margin-top: -10px;
        width: 3px;
        height: 20px;
    }

    .re_pic .repic_tip {
        position: absolute;
        bottom: 0.15rem;
        left: 0;
        color: #d2d2d2;
        font-size: 0.4rem;
        text-align: center;
    }

    .pic_wrap .repic_file {
        width: 80px;
        height: 80px;
        z-index: 2;
        position: absolute;
        opacity: 0;
        filter: alpha(opacity=0);
    }

    .re_pic .uploading {
        width: 80%;
        position: relative;
        height: 80%;
        margin-left: 10%;
        margin-top: 10%;
        z-index: 3;
    }

    .re_pic .upload_after {
        position: absolute;
        width: 100%;
        height: 100%;
        z-index: 3;
    }

    .re_pic .pic_del {
        width: 15px;
        height: 15px;
        -webkit-border-radius: 50% !important;
        -moz-border-radius: 50% !important;
        border-radius: 50% !important;
        background: #666;
        font-size: 16px;
        font-weight: 700;
        text-align: center;
        line-height: 15px;
        color: #fff;
        position: absolute;
        right: -7px;
        top: -7px;
        z-index: 10;
        cursor: pointer;
    }

    img {
        width: 80px;
        height: 80px;
    }

    .upTip {
        padding-top: 10px;
        color: #666;
        line-height: 1.6;
    }

    .upTip p {
        margin: 0;
    }

    .upTip em {
        color: #e7505a;
        font-style: normal;
    }
    .pic_wrap .upload_before{
        position: relative;
        border: 1px solid #ccc;
        width: 80px;
        height: 80px;
    }
</style>
<script src="https://cdn.bootcss.com/vue/2.4.0/vue.min.js"></script>
<div id="picWrap" class="pic_wrap">
     <div class="pic_list" id="pic_list">
        <!-- 图片上传 -->
        <div class="re_pic" v-for="(item,index) in numList">
            <!-- 上传后 -->
            <div class="upload_after" v-if="picList[index]">
                <span class="pic_del" @click="delImg(index)">-</span>
                <img :src="picList[index]" alt="">
            </div>
        </div>
    </div>
    <div class="upload_before" v-if="isLoadShow">
        <div class="repic_top"></div>
        <input type="file" accept="image/gif,image/jpeg,image/png" class="repic_file" @change="upImg($event)">
    </div>
</div>

<script src="/js/Sortable.min.js"></script>
<script>
    var picList = new Array();
    <?
    $arrPic = json_decode($data, true);
    foreach ($arrPic as $pic) {
        echo "picList[picList.length] = '" . \Yii::$app->params['sUploadUrl'] . "/$pic';\n";
    }
    ?>
    var pics = new Vue({
        el: "#picWrap",
        data: {
            numList: [], //用于存储图片index
            picList: picList, //存储图片
            num: 66,
            isLoadShow: true,
        },
        mounted: function () {
            var _this = this;
            _this.init();

            var el = document.getElementById('pic_list');
            var sortable = Sortable.create(el,{
                onEnd: function(evt) {
                    _this.imgResize()
                }
            });
        },
        methods: {
           //图片数组位置重新计算
            imgResize:function() {
                var _this = this;
                var len = $('.upload_after').length;
                for( var i = 0; i< len; i++ ){
                    _this.picList[i] = $('.upload_after').eq(i).find('img').attr('src');
                }
            },
            //编辑时初始化
            init: function () {
                var len = this.picList.length;
                if (len > 0) {
                    for (var i = 0; i < len; i++) {
                        if (i > 4) {
                            break;
                        }
                        this.numList.push(i);
                    }
                }
                if( len == 5 || len >5 ) {
                     this.isLoadShow = false;
                }
            },
            upImg: function (event) {
                var _this = this;
                var file = event.target.files[0] || event.srcElement.files[0],
                    reader = new FileReader();
                if (file.type.indexOf('image') == -1) { // 判断是否是图片
                    error('请上传正确的图片格式');
                    return;
                }

                var maxSize = 3000000; //设置上传图片的质量 这里是3M
                if (file.size > maxSize) {
                    error("上传图片大小不得大于3M");
                    return;
                }
                ;

                reader.readAsDataURL(file);
                reader.onload = function () {
                    
                    var result = this.result;

                    _this.picList.push(result);
                    if (_this.numList.length < 5) {
                        _this.numList.push(_this.num++);
                    }
                    if( _this.numList.length > 4 ) {
                        _this.isLoadShow = false;
                    }
                };
                $('.repic_file').val('');
            },
            delImg: function (index) {
                this.picList.splice(index, 1);
                this.numList.splice(index, 1);

                if (this.picList.length === 0) {
                    this.picList = [];
                    this.numList = [];
                }
                if( this.numList.length < 5 ) {
                    this.isLoadShow = true;
                }
            },
        }
    })
</script>
<input type="hidden" class="form-control" sdatatype="Text" sfieldas="sPic"
        placeholder="" value="111"name="arrObjectData[Shop/Product][sPic]">
