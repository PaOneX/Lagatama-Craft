<tr>
    <td><?= (int) $d['id'] ?></td>
    <td><?= htmlspecialchars($d['fname'] . ' ' . $d['lname']) ?></td>
    <td><?= htmlspecialchars($d['email']) ?></td>
    <td><?= htmlspecialchars($d['mobile']) ?></td>
    <td>
        <?php if ((int) $d['status'] === 1): ?>
            <span class="admin-badge admin-badge-success">Active</span>
        <?php else: ?>
            <span class="admin-badge admin-badge-danger">Inactive</span>
        <?php endif; ?>
    </td>
    <?php if (isset($d['joined_date'])): ?>
    <td><?= htmlspecialchars($d['joined_date']) ?></td>
    <?php endif; ?>
</tr>
