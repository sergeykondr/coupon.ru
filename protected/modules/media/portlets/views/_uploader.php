<div class="fileupload-buttonbar">
            <span class="btn btn-success fileinput-button">
                <i class="icon-plus icon-white"></i>
                <span>Добавить файлы...</span>
                <input type="file" name="file" multiple="">
            </span>
    <br class="clear"/>
</div>
<div id="<? echo $this->id ?>-drop-zone" class="drop-zone">
    Перетащите сюда файлы
</div>
<div class="fileupload-content">
    <table class="files">
        <thead>
        <tr>
            <th style="width: 60px"></th>
            <? foreach ($this->fields as $header)
        { ?>
            <th style="width: 250px"><? echo $header['header'];?></th>
            <? } ?>
            <th style="width: 50px"></th>
            <th style="width: 50px">Удалить</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
    <div class="fileupload-progressbar"></div>
</div>
