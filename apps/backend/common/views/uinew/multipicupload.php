<style>
 	.pic_wrap {
		display: -webkit-box;
		display: -webkit-flex;
		display: flex;
		display: box;
		position: relative;
		width: 600px;
	}
	.re_pic {
		width: 80px;
		height: 80px;
		border: 1px solid #ccc;
		position: relative;
		margin-right: 10px;
	}
	.re_pic .repic_top {
		position: absolute;
		height: 80px;
		width: 80px;
		left: 0;
		top: 0;
	}
	.re_pic .repic_top:after,
	.re_pic .repic_top:before {
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
	.re_pic .repic_top:before {
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
	.re_pic .repic_file {
		width: 100%;
		height: 100%;
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
		-webkit-border-radius: 50%!important;
		-moz-border-radius: 50%!important;
		border-radius: 50%!important;
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
		width: 100%;
		height: 100%;
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
</style>
<div class="pic_wrap" id="picWrap">
	<!-- 图片上传 -->
	<div class="re_pic" v-for="(item,index) in numList">
		<!-- 上传前 -->
		<div class="upload_before">
			<div class="repic_top"></div>
			<input type="file" accept="image/gif,image/jpeg,image/png" class="repic_file" @change="upImg($event)">
		</div>
		<!-- 上传后 -->
		<div class="upload_after" v-if="picList[index]">
			<span class="pic_del" @click="delImg(index)">-</span>
			<img :src="picList[index]" alt="">
		</div>
	</div>
</div>

<div class="upTip">	

</div>

<script>
	new Vue({
		el: "#picWrap",
		data: {
			numList: [0], //用于存储图片index
			picList:[], //存储图片
			num: 66,
		},
		mounted: function() {

		},
		methods: {
			upImg:function(event) {
				var _this = this;
				var file = event.target.files[0] || event.srcElement.files[0],
					reader = new FileReader();
				if(file.type.indexOf('image') == -1){ // 判断是否是图片
                    error('请上传正确的图片格式');
	                return;
	            }

	            var maxSize = 2000000; //设置上传图片的质量 这里是3M
			    if( file.size > maxSize ) {
			   		error("上传图片大小不得大于2M");
			   		return;
			    };

			     reader.readAsDataURL(file);
			     reader.onload = function() {
			     	
			     	var result = this.result;

			     	_this.picList.push(result);
				    if ( _this.numList.length < 5) {
					  _this.numList.push( _this.num++ );
					}

			     };
			},
			delImg: function(index) {
				
				$('.repic_file').eq(index).val('');

				this.picList.splice(index,1);

				if( this.picList.length === 0 ) {
					this.picList = [];
					this.numList = [];

					this.numList.push(0);
				}

				if( this.picList.length ===1 ) {
					this.numList.splice(index,1);
				}
				if( this.picList.length === 2 ) {
					this.numList.splice(index,1);
				}
				if( this.picList.length === 3 ) {
					this.numList.splice(index,1);
				}

				console.log(this.picList);
			},
		}
	})
</script>