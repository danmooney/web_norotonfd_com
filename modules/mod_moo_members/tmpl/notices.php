<h2 class="semibold heading">Notices</h2>

<?php if (!empty($notices)): ?>
    <ul>
    <?php foreach ($notices as $notice): ?>
              <li>
                  <?=
                      '<span class="date">' . date('m.d.Y', strtotime($notice->date)) . '</span><span class="separator">|</span>'
                    . '<a href="' . JRoute::_('index.php?option=com_moonotice&Itemid=148&tmpl=component&cid=' . $notice->notice_id) . '">' . $helper->truncate($notice->title, 80) . '</a>' ?>
              </li>
    <?php endforeach ?>
    </ul>
<?php else: ?>
    <p>There are currently no notices.</p>
<?php endif ?>