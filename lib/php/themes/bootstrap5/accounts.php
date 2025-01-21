<?php declare(strict_types=1);
// lib/php/themes/bootstrap5/accounts.php 20170225 - 20250121
// Copyright (C) 2015-2025 Mark Constable <markc@renta.net> (AGPL-3.0)

class Themes_Bootstrap5_Accounts extends Themes_Bootstrap5_Theme
{
    public function create(array $in): string
    {
        elog(__METHOD__);

        return $this->modal([
            'id'        => 'createUserModal', // Add this line
            'title'     => 'Create new user',
            'action'    => 'create',
            'lhs_cmd'   => '',
            'rhs_cmd'   => 'Create',
            'body'      => $this->modal_body($in)
        ]);
    }

    public function read(array $in): string
    {
        elog(__METHOD__);

        return $this->modal([
            'title'     => 'Update user',
            'action'    => 'update',
            'lhs_cmd'   => 'Delete',
            'rhs_cmd'   => 'Update',
            'body'      => $this->modal_body($in)
        ]);
    }

    public function delete(): ?string
    {
        elog(__METHOD__);

        $usr = db::read('login', 'id', $this->g->in['i'], '', 'one');

        return $this->modal_content([
            'title'     => 'Remove User',
            'action'    => 'delete',
            'lhs_cmd'   => '',
            'rhs_cmd'   => 'Remove',
            'hidden'    => sprintf('<input type="hidden" name="i" value="%s">', $this->g->in['i']),
            'body'      => sprintf('<p class="text-center">Are you sure you want to remove this user?<br><b>%s</b></p>', $usr['login']),
        ]);
    }

    public function list(array $in): string
    {
        elog(__METHOD__);

        return <<<HTML
        <div class="row">
            <h1>
                <i class="bi bi-people-fill"></i> Accounts
                <a href="?o=accounts&m=create" class="bslink" title="Add new account">
                    <i class="bi bi-plus-circle-fill fs-3"></i>
                </a>
            </h1>
        </div>
        <div class="table-responsive">
            <table id="accounts" class="table table-borderless table-striped datatable">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Alt Email</th>
                        <th>ACL</th>
                        <th>Grp</th>
                    </tr>
                </thead>
                <tfoot></tfoot>
            </table>
        </div>
        <div class="modal fade" id="createmodal" tabindex="-1" role="dialog" aria-labelledby="createmodal" aria-hidden="true">
            <div class="modal-dialog" id="createdialog">
            </div>
        </div>
        <div class="modal fade" id="readmodal" tabindex="-1" role="dialog" aria-labelledby="readmodal" aria-hidden="true">
            <div class="modal-dialog" id="readdialog">
            </div>
        </div>
        <div class="modal fade" id="deletemodal" tabindex="-1" role="dialog" aria-labelledby="deletemodal" aria-hidden="true">
            <div class="modal-dialog" id="deletedialog">
            </div>
        </div>
        <script>
        $(document).ready(function() {
            $('#accounts').DataTable({
                scrollY: '50vh',
                scrollCollapse: true,
                paging: true,
                order: [[0, 'asc']],
                autoWidth: false,
                ajax: {
                    url: '?o=accounts&x=json',
                    dataSrc: 'data'  // Important: DataTables expects data in a 'data' property
                },
                columns: [
                    { data: 0 }, // login with formatter
                    { data: 1 }, // fname
                    { data: 2 }, // lname
                    { data: 3 }, // altemail
                    { data: 4 }, // acl with formatter
                    { data: 5 }  // grp
                ],
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                    '<"row"<"col-sm-12"tr>>' +
                    '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
            });

            $(document).on("click", ".bslink", function(event){
                event.preventDefault();
                var url = $(this).attr("href") + "&x=html";
                var m = new URLSearchParams(url).get("m");
                $("#" + m + "dialog").load(url, function() {
                    $("#" + m + "modal", document).modal("show");
                });
            });
        });
        </script>
        HTML;
    }

    private function modal_body(array $in): string
    {
        elog(__METHOD__);

        $acl = $_SESSION['usr']['acl'];
        $grp = $_SESSION['usr']['grp'];

        $acl_ary = array_map(fn(string $k, string $v): array => [$v, $k], array_keys($this->g->acl), $this->g->acl);
        $acl_buf = $this->dropdown($acl_ary, 'acl', "{$acl}", '', 'form-select');

        $res = db::qry('SELECT login, id FROM `accounts` WHERE acl IN (0, 1)');
        $grp_ary = array_map(fn(array $row): array => [$row['login'], $row['id']], $res);
        $grp_buf = $this->dropdown($grp_ary, 'grp', "{$grp}", '', 'form-select');

        $aclgrp_buf = <<<HTML
        <div class="row">
            <div class="col-6 mb-3">
                <label for="acl" class="form-label">ACL</label>$acl_buf
            </div>
            <div class="col-6 mb-3">
                <label for="grp" class="form-label">Group</label>$grp_buf
            </div>
        </div>
        HTML;

        return <<<HTML
        <div class="row">
            <div class="col-6 mb-3">
                <label for="login" class="form-label">Email ID</label>
                <input type="email" class="form-control" id="login" name="login" value="{$in['login']}" required>
            </div>
            <div class="col-6 mb-3">
                <label for="altemail" class="form-label">Alt Email</label>
                <input type="text" class="form-control" id="altemail" name="altemail" value="{$in['altemail']}">
            </div>
        </div>
        <div class="row">
            <div class="col-6 mb-3">
                <label for="fname" class="form-label">First Name</label>
                <input type="text" class="form-control" id="fname" name="fname" value="{$in['fname']}" required>
            </div>
            <div class="col-6 mb-3">
                <label for="lname" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="lname" name="lname" value="{$in['lname']}" required>
            </div>
        </div>
        $aclgrp_buf
        HTML;
    }
}
