<div class="form_group" v-if="!tableView">
    <!--  <div class="form-h">商品规格</div> -->
    <div class="form-item" v-for="(attr,index) in attrs" :key="`attr${index}`">
        <div class="form-title">
            <input type="text" name="" value="" v-model="attr.pName" placeholder="规格名" maxlength='6' readonly>
        </div>
        <ul class="form-list">
            <li v-for="(item,index2) in attr.spec" :key="`item${index2}`" :class="{ 'active':!index&&checkBtn }">
                <input class="spec-item" type="text" maxlength='6' name="" value="" v-model="item.cName"
                       @input="changeTableList" readonly>

                <div class="load_img" v-if="!index&&checkBtn">
                    <div class="arrow"></div>
                    <div class="load_content">
                        ＋
                    </div>
                    <!--  图片显示位置 -->
                    <!-- 显示条件：imgFiles数组对应的index2位置 有图片 -->
                    <div class="image-box" v-if="imgFiles[index2]">
                        <img :src="imgFiles[index2]" alt="">
                    </div>
                </div>
            </li>
        </ul>
    </div>
    <div class="form-btn-group">
        该商品已上架，不可添加规格
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
                    <input type="text" maxlength='6' class="v_price required_input" v-model="tbList[getCNames(index)]&&tbList[getCNames(index)]['price']" >
                </td>
                <td>
                    <input type="text" maxlength='6' class="v_price required_input" v-model="tbList[getCNames(index)]&&tbList[getCNames(index)]['buyerPrice']" >
                </td>
                <td>
                    <input type="text" maxlength='6' class="sell required_input" v-model="tbList[getCNames(index)]&&tbList[getCNames(index)]['number']" >
                </td>
                <td>
                    <input type="text" maxlength='6' class="v_price " v-model="tbList[getCNames(index)]&&tbList[getCNames(index)]['buyingPrice']" >
                </td>
            </tr>
            </tbody>
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
