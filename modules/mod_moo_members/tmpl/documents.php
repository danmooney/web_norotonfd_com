<h2 class="semibold heading">Documents</h2>
<?php if (!empty($documents)): ?>

      <ul>
    <?php foreach ($documents as $document): ?>
              <li>
                  <?=
                      '<span class="date">' . date('m.d.Y', strtotime($document->date)) . '</span><span class="separator">|</span>'
                    . '<a target="_blank" href="/files/documents/' . $document->filename . '">' . $helper->truncate($document->title, 80) . '</a>'
                  ?></li>
    <?php endforeach ?>
      </ul>
<?php else: ?>
        <p>There are currently no documents.</p>
<?php endif ?>
