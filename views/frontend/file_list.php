<div class="file-list">
    <?php if (0 == $offset && 0 == count($link_list)) : ?>
        <div class="alert alert-info">
            Keine gefunden
        </div>
    <?php endif; ?>

    <?php foreach ($link_list as $link): ?>
        <li class="list-group-item">
            <a href="" onclick="returnFile(this)"
               data-value="<?= rex_extension::registerPoint(new rex_extension_point('TINYMCE_FILELIST_URL', $link['url'], [
                   'type' => $type,
                   'item' => $link,
               ])) ?>"
            ><?php echo $link['name']; ?></a>
        </li>
    <?php endforeach; ?>
    <?php if ($total > $offset + $limit): ?>
        <br/>
        <a class="btn btn-default btn-block"
           href="<?php echo $UrlService->getAjaxUrl('/file/index', [
               'ofl'         => 1,
               'page'        => $page + 1,
               'type'        => $type,
               'category_id' => $category_id,
               'clang_id'    => $clang_id,
               'search'      => $search,
               'ts'          => time(),
           ]); ?>" onclick="return loadMore(this)">Mehr</a>
        <br/>
        <br/>
    <?php endif; ?>
</div>