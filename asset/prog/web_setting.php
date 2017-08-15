<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>控制台</title>
    <link rel="stylesheet" href="../css/dracula.css">
    <link rel="stylesheet" href="../css/default/style.min.css" />
    <link rel="stylesheet" href="../css/main.css">
    <link rel=stylesheet href="../CodeMirror/doc/docs.css">

    <link rel="stylesheet" href="../CodeMirror/lib/codemirror.css">
    <script src="../js/uploadfile.js" charset="utf-8"></script>
    <style>
        html {
            margin: 0;
            padding: 0;
            font-size: 62.5%;
        }
        
        h1 {
            font-size: 1.8em;
        }
        
        .demo {
            overflow: scroll;
            border: 1px solid silver;
            min-height: 100%;
            width: 30%;
            position: absolute;
            left: 0;
        }
        
        .work_space {
            position: absolute;
            right: 0;
            border: 1px solid silver;
            min-height: 100%;
            width: calc(70% - 4px);
            overflow: hidden;
        }
        
        .CodeMirror {
            border: 1px solid black;
            height: 100%;
            font-size: 13px;
            position: relative;
            float: left;
            width: 100%;
        }
        
        #static {
            font-size: 20px;
            color: #d2d4d8;
            display: block;
            min-width: 30%;
            float: left;
        }
        
        #file_title {
            float: left;
            font-size: 1.5em;
            text-align: center;
        }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.1/jquery.min.js"></script>
    <script src="../js/jstree.js"></script>
    <script src="../CodeMirror/lib/codemirror.js"></script>
    <script src="../CodeMirror/mode/htmlmixed/htmlmixed.js"></script>
    <script src="../CodeMirror/mode/php/php.js"></script>
    <script src="../CodeMirror/mode/xml/xml.js"></script>
    <script src="../CodeMirror/mode/javascript/javascript.js"></script>
    <script src="../CodeMirror/addon/selection/active-line.js"></script>
    <script src="../CodeMirror/addon/edit/matchbrackets.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>

<body>
    <?php
        require_once('db_conf.php');
        $name = $_POST['name'];
        $to = array();
       # print("select * from tb_webpage where Page_Own like '".$name."'");
          array_push($to,json_encode(array("id"=>0,"parent"=>"#","text"=>$name,"a_attr" =>array("href"=>"/var/www/html/".$name),"static"=>array("selected"=>"true"))));
        $list = $db->query("select * from tb_webpage where Page_Own like '".$name."'");
        if (sizeof($list)>0){
       
              foreach( $list as $row){
                if($row['Page_Type']=="1"){
                   array_push($to,json_encode(array("id"=>$row['Page_Id'],"parent"=>$row['Page_Parent'],"text"=>$row['Page_Name'], "a_attr" =>array("href"=>$row['Page_Path']),JSON_UNESCAPED_SLASHES,"type"=>"file","icon"=> "jstree-file"),JSON_UNESCAPED_SLASHES)); 
                }else{
                   array_push($to,json_encode(array("id"=>$row['Page_Id'],"parent"=>$row['Page_Parent'],"text"=>$row['Page_Name'], "a_attr" =>array("href"=>$row['Page_Path']),JSON_UNESCAPED_SLASHES,"type"=>"folder","icon"=> "jstree-folder"),JSON_UNESCAPED_SLASHES));  
                }
              }
            }
        
     
        print('<script>var own = "'.$name.'";</script>');
        print('<script>var jsondata = ['.implode(",",$to).'];</script>');
    ?>
        <div id="data" class="demo"></div>
        <div class="work_space">
            <div id="work_head">
                <span id="file_title" style="width:70%"></span>
                <span id="static" style="width:30%"></span>
            </div>

            <textarea id="code" name="code">
                function findSequence(goal) { function find(start, history) { if (start == goal) return history; else if (start > goal) return null; else return find(start + 5, "(" + history + " + 5)") || find(start * 3, "(" + history + " * 3)"); } return find(1, "1"); }
            </textarea>
        </div>

        <script type="text" id="add_file_modal">
            <div class="popup-wrapper" style="min-width: 400px;">

                <div class="popup-header">新增公告</div>

                <div class="popup-content">
                    <form method="post" id="change_obj" enctype="multipart/form-data">
                        <div class="form-group">
                            <input type="text" placeholder="檔案名稱" name="Btitle" id="Btitle">
                        </div>

                        <div class="form-group">
                            <input type="file" name="mfile" id="mfile">
                        </div>


                        <div class="form-group">
                            <button type="button" class="btn-danger ok" id="send_file" style="width: 45%;">送出</button>
                            <button type="button" class="btn-success cancel" style="width: 45%;">取消</button>

                        </div>
                    </form>
                </div>
            </div>
        </script>
        <script src="../js/timodal.min.js" charset="utf-8"></script>

        <script>
            // html demo
            var fd = null;
            var allfiletype = [

                'html',
                'txt',
                'htm'
            ];
            $('#data')
                .jstree({
                    'core': {
                        "animation": 0,
                        "check_callback": true,
                        "themes": {
                            "stripes": true
                        },
                        'data': jsondata,
                        'check_callback': function (o, n, p, i, m) {
                            if (m && m.dnd && m.pos !== 'i') {
                                return false;
                            }
                            if (o === "move_node" || o === "copy_node") {
                                if (this.get_node(n).parent === this.get_node(p).id) {
                                    return false;
                                }
                            }
                            return true;
                        }
                    },
                    'sort': function (a, b) {
                        return this.get_type(a) === this.get_type(b) ? (this.get_text(a) > this.get_text(b) ? 1 : -1) : (this.get_type(a) >= this.get_type(b) ? 1 : -1);
                    },
                    'contextmenu': {
                        'items': function (node) {
                            var tmp = $.jstree.defaults.contextmenu.items();
                            console.log(tmp);
                            delete tmp.create.action;
                            delete tmp.remove.action;
                            delete tmp.rename.action;

                            tmp.rename.label = "重新命名";
                            tmp.remove.label = "刪除";
                            tmp.create.label = "新增";
                            tmp.ccp.label = "編輯";
                            tmp.ccp.submenu.cut.label = "剪下";
                            tmp.ccp.submenu.copy.label = "複製";
                            tmp.ccp.submenu.paste.label = "貼上";
                            tmp.go.action = function (data) {
                                if (node.type == "default") {
                                    alert('這是資料夾喔');
                                } else {
                                    var inst = $.jstree.reference(data.reference),
                                        obj = inst.get_node(data.reference);
                                    file_path = obj.a_attr.href;
                                    var temp1 = file_path.slice(14, file_path.length);
                                    window.open("http://" + window.location.hostname + "/" + temp1, '_blank');
                                    console.log('go');
                                }

                            }
                            tmp.create.submenu = {
                                "create_file": {
                                    "label": "檔案",
                                    "action": function (data) {
                                        var inst = $.jstree.reference(data.reference),
                                            obj = inst.get_node(data.reference);

                                        var parentname = obj.id;
                                        //                                        var a = prompt("檔案名稱", "");


                                        var html = $('#add_file_modal').html();


                                        tiModal.create(html, {
                                            events: {
                                                'click .cancel': function (e) {
                                                    //this.close();
                                                    this.close();
                                                },

                                            },
                                            modal: true
                                        }).show();
                                        var select_file = false;
                                        var fd = null;
                                        //
                                        //
                                        //                                        $('#mfile').change(function (event) {
                                        //                                            //start 
                                        //
                                        //                                            var FR = new FileReader();
                                        //                                            FR.onload = function (e) {
                                        //                                                fd = e.target.result;
                                        //                                                console.log(fd);
                                        //                                            }
                                        //                                            FR.readAsDataURL(this.files[0]);
                                        //
                                        //                                        });
                                        $("#send_file").click(function (e) {

                                            var a = $('#Btitle').val();
                                            var form = $('#change_obj')[0];
                                            console.log(form); // You need to use standard javascript object here
                                            var formdata = new FormData(form);
                                            console.log(formdata);
                                            formdata.append('parent', parentname);
                                            formdata.append('Page_Own', own);
                                            formdata.append('path', obj.a_attr.href + "/" + a);
                                            formdata.append('name', a);



                                            //                                            formdata.push({
                                            //                                                name: 'mfile',
                                            //                                                value: fd
                                            //                                            });
                                            //                                                parent: parentname,
                                            //                                                Page_Own: own,
                                            //                                                path: obj.a_attr.href + "/" + a
                                            $.ajax({
                                                url: "upload.php",
                                                type: "POST",
                                                contentType: false,
                                                processData: false,
                                                cache: false,
                                                data: formdata,
                                                success: function (data1) {
                                                    console.log("----------------");
                                                    console.log(data1);
                                                    console.log("----------------");
                                                    tiModal.close();
                                                    inst.create_node(obj, {
                                                        type: "file",
                                                        "icon": "jstree-file",
                                                        text: a,
                                                        a_attr: {
                                                            href: obj.a_attr.href + "/" + a
                                                        }

                                                    }, "last", function (new_node) {

                                                        setTimeout(function () {
                                                            inst.open_node(obj);

                                                        }, 0);
                                                    });

                                                },
                                                error: function (data1) {
                                                    console.log("----------------");
                                                    console.log(data1);
                                                    console.log("----------------");

                                                }
                                            });

                                            // tiModal.close();

                                            //                                                $.post("add_node.php", {
                                            //                                                name: a,
                                            //                                                parent: parentname,
                                            //                                                Page_Own: own,
                                            //                                                path: obj.a_attr.href + "/" + a
                                            //                                            }).done(function (data) {
                                            //                                           
                                            //                                            });

                                        });
                                        //                                        $.post("add_node.php", {name:""}).done(function (data) {
                                        //                                            if (data.indexOf('suc') < -1) {
                                        //                                              
                                        //                                            }
                                        //                                        });

                                    }
                                },
                                "create_folder": {
                                    "separator_after": true,
                                    "label": "資料夾/群組",
                                    "action": function (data) {
                                        var inst = $.jstree.reference(data.reference),
                                            obj = inst.get_node(data.reference);

                                        var parentname = obj.id;
                                        var a = prompt("資料夾名稱", "");
                                        $.post("add_folder.php", {
                                            name: a,
                                            parent: parentname,
                                            Page_Own: own,
                                            path: obj.a_attr.href + "/" + a
                                        }).done(function (data) {
                                            console.log(obj.a_attr);
                                            inst.create_node(obj, {
                                                type: "default",
                                                icon: "jstree-folder",
                                                text: a,
                                                a_attr: {
                                                    href: obj.a_attr.href + "/" + a
                                                }
                                            }, "last", function (new_node) {
                                                console.log(123);
                                                setTimeout(function () {
                                                    inst.open_node(obj);
                                                }, 0);
                                            });
                                        });

                                        //                                        $.post("add_node.php", {name:""}).done(function (data) {
                                        //                                            if (data.indexOf('suc') < -1) {
                                        //                                              
                                        //                                            }
                                        //                                        });

                                    }
                                }
                            };
                            if (this.get_type(node) === "file") {
                                delete tmp.create;
                            }
                            return tmp;
                        }
                    },
                    "types": {
                        "#": {

                            "valid_children": ["root"]
                        },
                        "root": {
                            "icon": "/static/3.3.4/assets/images/tree_icon.png",
                            "valid_children": ["default", "file"]
                        },
                        "default": {
                            "valid_children": ["default", "file"]
                        },
                        "file": {
                            "icon": "jstree-file",
                            "valid_children": ["file"]
                        }
                    },

                    'unique': {
                        'duplicate': function (name, counter) {
                            return name + ' ' + counter;
                        }
                    },
                    'plugins': ['state', 'dnd', 'sort', 'types', 'contextmenu', 'unique']
                }).on('changed.jstree', function (e, data) {
                    var a = data.node;
                    if (a && a != null && typeof (a.a_attr) != 'undefined') {

                        console.log(a);
                        var temp = a.a_attr.href;
                        file_path = temp;
                        var temp1 = temp.slice(14, temp.length);
                        if (a.type != "folder" && a.type != "default") {
                            var filetype = temp1.split('.')[1];
                            if (allfiletype.indexOf(filetype) > -1) {
                                $.post("get_file.php", {
                                    path: temp
                                }).done(function (data) {
                                    $('#file_title').text(temp1);
                                    //                                console.log("/"+temp);
                                    //                                console.log(data);
                                    editor.getDoc().setValue(data);
                                });
                            } else {
                                editor.getDoc().setValue('目前不支援這種檔案唷');
                            }

                        } else {
                            //       console.log(a);
                            var temp = a.a_attr.href;
                            file_path = temp;
                            var temp1 = temp.slice(14, temp.length);
                            $('#file_title').text(temp1);
                            editor.getDoc().setValue('這是資料夾');
                            //                            $.post("get_file.php",{path:temp+"/config.json"}).done(function(data){
                            //                                $('#file_title').text(temp1);
                            //                                console.log("/"+temp);
                            //                                console.log(data);
                            //                                editor.getDoc().setValue(data);
                            //                            });
                        }


                    } else {

                    }


                }).on('move_node.jstree', function (e, data) {
                    //console.log('move');
                    var inst = $('#data').jstree(true).get_node(data.parent);
                    console.log(data);
                    $.post('move_node.php', {
                        parent: data.parent,
                        id: data.node.id,
                        oldpath: data.node.a_attr.href,
                        path: inst.a_attr.href + "/" + data.node.text
                    }).done(function (data1) {
                        console.log(data1);
                        data.node.a_attr.href = inst.a_attr.href + "/" + data.node.text;
                    });

                }).on('hover_node.jstree', function (e, data) {
                    //console.log('move');
                    
target = data.node.text;
                });
            var target = "";

            var file_drop = document.getElementById('data');
            file_drop.addEventListener(
                'dragover',
                function handleDragOver(evt) {
                    evt.stopPropagation()
                    evt.preventDefault()
                    evt.dataTransfer.dropEffect = 'copy'
                },
                false
            )
            file_drop.addEventListener(
                'drop',
                function (evt) {
                    evt.stopPropagation()
                    evt.preventDefault()
                    var files = evt.dataTransfer.files // FileList object.
                    var file = files[0] // File     object.
                    alert("目標節點"+target+"檔案名稱"+file.name)
                },
                false
            )
            var file_path = "";
            var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
                lineNumbers: true,
                styleActiveLine: true,
                matchBrackets: true,
                mode: "htmlmixed"
            });
            editor.on("blur", function () {
                $('#static').text('儲存中...');
                $.post('save_node.php', {
                    path: file_path,
                    content: editor.getDoc().getValue()
                }).done(function () {
                    $('#static').text('');
                });


            });
            editor.on("focus", function () {
                $('#static').text('編輯中...');


            });
        </script>
</body>

</html>