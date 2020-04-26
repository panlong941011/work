<div name="处理记录">
    <h3 class="form-section">处理记录</h3>
    <div class="row">
        <div class="col-md-8">
            <div class="table-scrollable">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th> 序号</th>
                        <th> 处理内容</th>
                        <th> 处理时间</th>

                    </tr>
                    </thead>
                    <tbody>
                    <? foreach ($arrLog as $key => $log) { ?>
                        <tr>
                            <td> <?=$key+1?></td>
                            <td> <?=$log[0]?></td>
                            <td> <?=$log[1]?></td>
                        </tr>
                    <? } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>