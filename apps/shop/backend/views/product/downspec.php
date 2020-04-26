<div class="form_group" v-if="!tableView">
    <!--  <div class="form-h">商品规格</div> -->
    <div class="form-item" v-for="(attr,index) in attrs" :key="`attr${index}`">
        <div class="form-title">
            <input type="text" name="" value="" v-model="attr.pName" placeholder="规格名" maxlength='6'>
            <span class="delete" @click="toDelete(index)">×</span>
        </div>
        <ul class="form-list">
            <li v-for="(item,index2) in attr.spec" :key="`item${index2}`" :class="{ 'active':!index&&checkBtn }">
                <input class="spec-item" type="text" maxlength='6' name="" value="" v-model="item.cName"
                       @input="changeTableList">

                <div class="load_img" v-if="!index&&checkBtn">
                    <div class="arrow"></div>
                    <div class="load_content">
                        ＋
                        <input type="file" class="load_img_file" @change='upImg(index2)'
                               accept="image/gif, image/jpeg, image/png">
                    </div>
                    <!--  图片显示位置 -->
                    <!-- 显示条件：imgFiles数组对应的index2位置 有图片 -->
                    <div class="image-box" v-if="imgFiles[index2]">
                        <img :src="imgFiles[index2]" alt="">
                        <span class="img_close_btn" @click="delImg(index2)">×</span>
                    </div>
                </div>
            </li>
        </ul>
        <label for="bImage" class="addImg" v-show="!index">
            <input type="checkbox" id="bImage" :checked="checkBtn" @click="addImage()">
            添加规格图片
        </label>
    </div>
    <div class="form-btn-group">
        <button class="btn" id="addBtn" type="button" name="" @click="addItem">添加规格项目</button>
    </div>
    <div class="form-table" v-show="tableData">
        <!--  <div class="stock-title">商品库存</div> -->
        <table class="table-sku">
            <thead>
            <tr>
                <th v-for="(list,index) in tableData" :key="`list${index}`">{{list['pName']}}</th>
                <th>
                    价格(元)<sup>*</sup>
                </th>
                <th>
                    渠道价(元)<sup>*</sup>
                </th>
                <th>数量<sup>*</sup>
                </th>
                <th>进货价格</th>
            </tr>
            </thead>
            <tbody>
            <tr class="table-sku-item" v-for="(row,index) in rows" :key="`row${index}`">
                <td v-for="(item,index2) in tableData" v-if="!((row-1)%item['rowspan'])" :rowspan="item['rowspan']"
                    :key="`item${index2}`">
                    {{item|getName(row)}}
                </td>
                <td>
                    <input type="text" maxlength='6' class="v_price required_input" v-model="tbList[getCNames(index)]&&tbList[getCNames(index)]['price']">
                </td>
                <td>
                    <input type="text" maxlength='6' class="v_price required_input" v-model="tbList[getCNames(index)]&&tbList[getCNames(index)]['buyerPrice']">
                </td>
                <td>
                    <input type="text" maxlength='6' class="sell required_input" v-model="tbList[getCNames(index)]&&tbList[getCNames(index)]['number']">
                </td>
                <td>
                    <input type="text" maxlength='6' class="v_price " v-model="tbList[getCNames(index)]&&tbList[getCNames(index)]['buyingPrice']">
                </td>
            </tr>
            </tbody>
            <!-- 批量操作结构 -->
            <tfoot v-if="batchShow||attrs.length">
            <tr>
                <td colspan="7">
                    <div class="batch">
                        批量设置：
                        <span class="batch_type">
                                <a href="javascript:;" @click="showInput(0)">价格</a>
                                <a href="javascript:;" @click="showInput(3)">渠道价</a>
                                <a href="javascript:;" @click="showInput(1)">库存</a>
                                <a href="javascript:;" @click="showInput(2)">进货价</a>
                            </span>
                        <span class="batch_form" v-show="batchForm">
                                <input type="text" placeholder="请输入" v-model='batchValue' class="b_value"
                                       maxlength='6'>
                                <a href="javascript:;" @click="closeInput(0)" class="keep_num">保存</a>
                                <a href="javascript:;" @click="closeInput(1)">取消</a>
                            </span>
                    </div>
                </td>
            </tr>
            </tfoot>
        </table>
        <!--  <div class="form-btn-group"><button class="btn" type="button" name="" @click="toConfirm">确认</button></div> -->
    </div>
</div>

<!-- 只展示部分 -->
<div class="form-table form_show" v-if="tableView && tableData">
    <table class="table-sku">
        <thead>
        <tr>
            <th v-for="(list,index) in tableData" :key="`list${index}`">{{list['pName']}}</th>
            <th>
                价格(元)<sup>*</sup>
            </th>
            <th>
                渠道价(元)<sup>*</sup>
            </th>
            <th>数量<sup>*</sup>
            </th>
            <th>进货价格</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(row,index) in rows" :key="`row${index}`">
            <td v-for="(item,index2) in tableData" v-if="!((row-1)%item['rowspan'])" :rowspan="item['rowspan']"
                :key="`item${index2}`">
                {{item|getName(row)}}
            </td>
            <td>
                {{ tbList[getCNames(index)]['price'] }}
            </td>
            <td>
                {{ tbList[getCNames(index)]['buyerPrice'] }}
            </td>
            <td>
                {{tbList[getCNames(index)]['number'] }}
            </td>
            <td>
                {{tbList[getCNames(index)]['buyingPrice']}}
            </td>
        </tr>
        </tbody>
    </table>
</div>
