<?php

require_once dirname(__DIR__) . '/init.php';

$pageTitle = 'User Management';
$activeNav = 'users';

admin_layout($pageTitle, $activeNav, function () {
    ?>
    <div class="admin-page-intro">
        <p>Search and manage registered customer accounts.</p>
    </div>
    <div class="admin-toolbar">
        <div class="admin-search-wrap">
            <i class="bi bi-search"></i>
            <input type="text" class="form-control" placeholder="Search by name or email..." id="user" onkeyup="if(event.key==='Enter')searchUser()">
        </div>
        <button class="admin-btn admin-btn-secondary" type="button" onclick="searchUser();">
            <i class="bi bi-search"></i> Search
        </button>
        <button class="admin-btn admin-btn-secondary" type="button" onclick="loadUser();">
            <i class="bi bi-arrow-clockwise"></i> Refresh
        </button>
        <input type="text" class="form-control" style="max-width:120px;" placeholder="User ID" id="uid">
        <button class="admin-btn admin-btn-info" type="button" onclick="updateUserStatus();">
            <i class="bi bi-toggle-on"></i> Toggle Status
        </button>
    </div>
    <div class="admin-table-wrap">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Status</th>
                        <th>Joined</th>
                    </tr>
                </thead>
                <tbody id="tb"></tbody>
            </table>
        </div>
    </div>
    <?php
}, ['onload' => 'loadUser();']);
