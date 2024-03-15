<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CodeIgniter log viewer</title>

    <link rel="stylesheet" href="<?php echo base_url('logviewer/bs5/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('logviewer/datatable/datatables.min.css'); ?>">
    <script src="<?php echo base_url('logviewer/jquery-3.7.1.min.js'); ?>"></script>
    <script src="<?php echo base_url('logviewer/bs5/js/bootstrap.bundle.min.js'); ?>"></script>
    <script src="<?php echo base_url('logviewer/datatable/datatables.min.js'); ?>"></script>



    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        body {
            padding: 25px;
        }

        h1 {
            font-size: 1.5em;
            margin-top: 0;
        }

        .date {
            min-width: 75px;
        }

        .text {
            word-break: break-all;
        }

        a.llv-active {
            z-index: 2;
            background-color: #f5f5f5;
            border-color: #777;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
            <h1><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> CodeIgniter Log Viewer</h1>
            <p class="text-muted"><i>by <a href="https://github.com/hi2rashid" target="_blank">Abdul Rashid</a></i></p>
			<p class="text-muted"><i>Worked in PHP 5.3 by <a href="https://afrizalmy.com" target="_blank">AFMY</a></i></p>
            <div class="list-group">
                <?php if(empty($files)): ?>
                    <a class="list-group-item liv-active">No Log Files Found</a>
                <?php else: ?>
                    <?php foreach($files as $file): ?>
                        <a href="?f=<?php echo base64_encode($file); ?>"
                           class="list-group-item <?php echo ($currentFile == $file) ? "llv-active" : "" ?>">
                            <?php echo $file; ?>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-sm-9 col-md-10 table-container">
            <?php if(is_null($logs)): ?>
                <div>
                    Log file >50M, please download it.
                </div>
            <?php else: ?>
                <table id="table-log" class="table table-striped">
                    <thead>
                    <tr>
                        <th>Level</th>
                        <th>Date</th>
                        <th>Content</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php foreach($logs as $key => $log): ?>
                        <tr data-display="stack<?php echo $key; ?>">

                            <td class="text-<?php echo $log['class']; ?>">
                                <span class="<?php echo $log['icon']; ?>" aria-hidden="true"></span>
                                &nbsp;<?php echo $log['level']; ?>
                            </td>
                            <td class="date"><?php echo $log['date']; ?></td>
                            <td class="text">
                                <?php if (array_key_exists("extra", $log)): ?>
                                    <a class="pull-right expand btn btn-default btn-xs"
                                       data-display="stack<?php echo $key; ?>">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </a>
                                <?php endif; ?>
                                <?php echo $log['content']; ?>
                                <?php if (array_key_exists("extra", $log)): ?>
                                    <div class="stack" id="stack<?php echo $key; ?>"
                                         style="display: none; white-space: pre-wrap;">
                                        <?php echo $log['extra'] ?>
                                    </div>
                                <?php endif; ?>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
            <div>
                <?php if($currentFile): ?>
                    <a href="?dl=<?php echo base64_encode($currentFile); ?>">
                        <span class="glyphicon glyphicon-download-alt"></span>
                        Download file
                    </a>
                    -
                    <a id="delete-log" href="?del=<?php echo base64_encode($currentFile); ?>"><span
                                class="glyphicon glyphicon-trash"></span> Delete file</a>
                    <?php if(count($files) > 1): ?>
                        -
                        <a id="delete-all-log" href="?del=<?php echo base64_encode("all"); ?>"><span class="glyphicon glyphicon-trash"></span> Delete all files</a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script>
    $(document).ready(function () {

        $('.table-container tr').on('click', function () {
            $('#' + $(this).data('display')).toggle();
        });

        $('#table-log').DataTable({
            "order": [],
            "stateSave": true,
            "stateSaveCallback": function (settings, data) {
                window.localStorage.setItem("datatable", JSON.stringify(data));
            },
            "stateLoadCallback": function (settings) {
                var data = JSON.parse(window.localStorage.getItem("datatable"));
                if (data) data.start = 0;
                return data;
            }
        });
        $('#delete-log, #delete-all-log').click(function () {
            return confirm('Are you sure?');
        });
        <?php if (isset($refresh_time) && (int)$refresh_time > 1000): ?>
              setInterval(function(){
                location.reload();
              }, <?php echo $refresh_time; ?>);
        <?php endif; ?>
    });
</script>
</body>
</html>
