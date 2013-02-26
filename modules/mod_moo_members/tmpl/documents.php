<h2 class="semibold heading">Documents</h2>
<?php if (!empty($documents)): ?>

      <ul>
    <?php foreach ($documents as $document): ?>
              <li><?= date('m.d.Y', strtotime($document->date)) . '|' . '<a href="#">' . $helper->truncate($document->title, 80) . '</a>' ?></li>
    <?php endforeach ?>
      </ul>
<?php else: ?>
        <p>There are currently no documents.</p>
<?php endif ?>
