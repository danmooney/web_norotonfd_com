<h2 class="semibold heading">Notices</h2>

<?php if (!empty($notices)): ?>
    <ul>
    <?php foreach ($notices as $notice): ?>
              <li><?= date('m.d.Y', strtotime($notice->date)) . '|' . '<a href="#">' . $helper->truncate($notice->title, 80) . '</a>' ?></li>
    <?php endforeach ?>
    </ul>
<?php else: ?>
    <p>There are currently no notices.</p>
<?php endif ?>