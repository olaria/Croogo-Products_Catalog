<?php
echo $this->Html->css('/products_catalog/css/jquery.fileupload-ui');
$this->Html->script(
    array(
        '/products_catalog/js/jquery.ui.widget.min',
        '/products_catalog/js/tmpl.min',
        '/products_catalog/js/load-image.min',
        '/products_catalog/js/jquery.iframe-transport',
        '/products_catalog/js/jquery.fileupload',
        '/products_catalog/js/jquery.fileupload-process',
        '/products_catalog/js/jquery.fileupload-resize',
        '/products_catalog/js/jquery.fileupload-validate',
        '/products_catalog/js/jquery.fileupload-ui',
        '/products_catalog/js/main',
    ),
    false
);
$this->extend('/Common/admin_edit');

$this->Html
    ->addCrumb('', '/admin', array('icon' => 'home'))
    ->addCrumb(__d('croogo', 'Content'), array('plugin' => 'nodes', 'controller' => 'nodes', 'action' => 'index'));

if ($this->request->params['action'] == 'admin_edit') {
    $this->Html
        ->addCrumb(
            __d('croogo', 'Products'),
            array('plugin' => 'products_catalog', 'controller' => 'products', 'action' => 'index',)
        )
        ->addCrumb($this->request->data['Product']['name'], $this->here);
}

if ($this->request->params['action'] == 'admin_add') {
    $this->Html
        ->addCrumb(
            __d('croogo', 'Products'),
            array('plugin' => 'products_catalog', 'controller' => 'products', 'action' => 'index',)
        )
        ->addCrumb(__d('croogo', 'Add'), $this->here);
}

echo $this->Form->create('Product', array('method' => 'POST'));

?>
    <div class="row-fluid">
        <div class="span8">
            <ul class="nav nav-tabs">
                <?php
                echo $this->Croogo->adminTab(__d('croogo', 'Product'), '#product-basic');
                echo $this->request->params['action'] == 'admin_edit' ? $this->Croogo->adminTab(__d('croogo', 'Images'), '#product-images') : "";
                echo $this->Croogo->adminTabs();
                ?>
            </ul>

            <div class="tab-content">

                <div id="product-basic" class="tab-pane">
                    <?php
                    echo $this->Form->input('id');
                    $this->Form->inputDefaults(
                        array(
                            'class' => 'span10',
                            'label' => false,
                        )
                    );
                    echo $this->Form->input(
                        'category_id',
                        array(
                            'label' => __d('croogo', 'Category'),
                        )
                    );
                    echo $this->Form->input(
                        'name',
                        array(
                            'label' => __d('croogo', 'Name'),
                        )
                    );
                    echo $this->Form->input(
                        'description',
                        array(
                            'label' => __d('croogo', 'Description'),
                        )
                    );
                    ?>
                </div>

                <?php if ($this->request->params['action'] == 'admin_edit'): ?>
                <div id="product-images" class="tab-pane" rel="<?= $this->Html->url(
                    array(
                        'plugin' => 'products_catalog',
                        'controller' => 'products',
                        'action' => 'upload_image',
                        $this->request->data['Product']['id']
                    )); ?>">
                    <div class="input file fileupload-buttonbar">
                        <div class="span7">
                            <!-- The fileinput-button span is used to style the file input field as button -->
                            <span class="btn btn-success fileinput-button">
                                <i class="icon-plus icon-white"></i>
                                <span>Add files...</span>
                                <input type="file" name="files[]" multiple>
                            </span>
                            <button type="submit" class="btn btn-primary start">
                                <i class="icon-upload icon-white"></i>
                                <span>Start upload</span>
                            </button>
                            <button type="reset" class="btn btn-warning cancel">
                                <i class="icon-ban-circle icon-white"></i>
                                <span>Cancel upload</span>
                            </button>
                            <button type="button" class="btn btn-danger delete">
                                <i class="icon-trash icon-white"></i>
                                <span>Delete</span>
                            </button>
                            <input type="checkbox" class="toggle">
                            <!-- The loading indicator is shown during file processing -->
                            <span class="fileupload-loading"></span>
                        </div>
                        <!-- The global progress information -->
                        <div class="span5 fileupload-progress fade">
                            <!-- The global progress bar -->
                            <div class="progress progress-success progress-striped active" role="progressbar"
                                 aria-valuemin="0" aria-valuemax="100">
                                <div class="bar" style="width:0%;"></div>
                            </div>
                            <!-- The extended global progress information -->
                            <div class="progress-extended">&nbsp;</div>
                        </div>
                    </div>
                    <!-- The table listing the files available for upload/download -->
                    <table role="presentation" class="table table-striped">
                        <tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></tbody>
                    </table>
                </div>
                <? endif; ?>

                <?php echo $this->Croogo->adminTabs(); ?>
            </div>
        </div>

        <div class="span4">
            <?php
            echo $this->Html->beginBox(__d('croogo', 'Publishing')) .
                $this->Form->button(__d('croogo', 'Save'), array('button' => 'default')) .
                $this->Html->link(
                    __d('croogo', 'Cancel'),
                    array('action' => 'index'),
                    array('button' => 'danger')
                ) .
                $this->Html->endBox();
            ?>
        </div>

    </div>
<?php echo $this->Form->end(); ?>
<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
    {% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td>
            <span class="preview"></span>
        </td>
        <td>
            <p class="name">{%=file.name%}</p>
            {% if (file.error) { %}
            <div><span class="label label-important">Error</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <p class="size">{%=o.formatFileSize(file.size)%}</p>
            {% if (!o.files.error) { %}
            <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="bar" style="width:0%;"></div></div>
            {% } %}
        </td>
        <td>
            {% if (!o.files.error && !i && !o.options.autoUpload) { %}
            <button class="btn btn-primary start">
                <i class="icon-upload icon-white"></i>
                <span>Start</span>
            </button>
            {% } %}
            {% if (!i) { %}
            <button class="btn btn-warning cancel">
                <i class="icon-ban-circle icon-white"></i>
                <span>Cancel</span>
            </button>
            {% } %}
        </td>
    </tr>
    {% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
    {% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        <td>
            <span class="preview">
                {% if (file.thumbnail_url) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" data-gallery="gallery" download="{%=file.name%}"><img src="{%=file.thumbnail_url%}"></a>
                {% } %}
            </span>
        </td>
        <td>
            <p class="name">
                <a href="{%=file.url%}" title="{%=file.name%}" data-gallery="{%=file.thumbnail_url&&'gallery'%}" download="{%=file.name%}">{%=file.name%}</a>
            </p>
            {% if (file.error) { %}
            <div><span class="label label-important">Error</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
        <td>
            <button class="btn btn-danger delete" data-type="{%=file.delete_type%}" data-url="{%=file.delete_url%}"{% if (file.delete_with_credentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
            <i class="icon-trash icon-white"></i>
            <span>Delete</span>
            </button>
            <input type="checkbox" name="delete" value="1" class="toggle">
        </td>
    </tr>
    {% } %}
</script>