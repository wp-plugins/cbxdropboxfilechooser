/**
 * Created by manchumahara on 9/15/14.
 */

(function ( $ ) {
    "use strict";

    $(function () {



        function insertDBImgtoEditor(link, width, height, filetype, fileicon) {
            //console.log(filetype);
            //wp.media.editor.insert('[myshortcode id="' + first.id + '"]');
            //console.log('inserting image');
            if(filetype == 'image'){
                wp.media.editor.insert('<img style="margin: 4px; float: left;" src="'+link+'"  width="'+width+'" height="'+height+'" />');
            }
            else{
                wp.media.editor.insert('<a href="'+link+'">'+link+'</a>');
            }


        }

//        var s           = document.createElement('script');
//        s.type          = 'text/javascript';
//        s.src           = 'https://www.dropbox.com/static/api/2/dropins.js';
//        s.id            = 'dropboxjs';
//        $(s).attr('data-app-key',  'nn8s09kicvbzpmf');
//
//        (document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);


        $(document).ready(function(){
            //console.log('hi there2');





            var button = Dropbox.createChooseButton({
                success: function(files) {

                    //nasty class fixed, after successfully picked the button's class name is reset
                    $(button).addClass('button dropboxfilechooserbtn');
                    $("#cbdrpboxchooserwrap").css({
                        'display' : 'block'
                    });

                    for (var i = 0; i < files.length; i++) {
                        var file        = files[i];
                       // console.log(file);
                        var icon        = file.icon;
                        //check file format and take special care for image
                        var fileext = file.link.substr((~-file.link.lastIndexOf(".") >>> 0) + 2).toLowerCase();

                        //console.log(icon);

                        var filelist    = new Array();

                        var filesingle0  = {};

                        filesingle0.link     = file.link;

                        //this trick helps to get the real public url
                        //filesingle0.link     =  file.link.replace('https://www.dropbox.com','https://dl.dropboxusercontent.com');

                        filesingle0.width    = '800px';
                        filesingle0.height   = 'auto';
                        filesingle0.title    = 'Main File, Click to insert, right click to copy';
                        filesingle0.text     = 'Main File';
                        filesingle0.icon     = icon;

                        var image_exts = ['bmp', 'cr2', 'gif', 'ico', 'ithmb', 'jpeg', 'jpg', 'nef', 'png', 'raw', 'svg', 'tif', 'tiff', 'wbmp', 'webp'];

                        //if(fileext == 'jpg' || fileext == 'gif' || fileext == 'bmp' || fileext =='jpeg' ){
                        if(jQuery.inArray(fileext, image_exts) > 0){
                            filesingle0.filetype     = 'image';
                        }
                        else{
                            filesingle0.filetype     = '';
                        }
                        filelist[0]     = filesingle0;  //original file

                        var inputclass   = "dropboxinput dropboxinput"+i;

                        /*
                         var inputbox     = $("div", {
                         'class': inputclass
                         }).inject(dropboxinputwrap, 'bottom');
                         */
                        var inputbox     = $("<div />", {
                            'class': inputclass
                        });

                        dropboxinputwrap.append(inputbox);

                        var inputboxsinglewrap = inputbox.wrap('<div class="dropboxinputsinglewrap" />');


                        //console.log(fileext);
                        //if(fileext == 'jpg' || fileext == 'gif' || fileext == 'bmp' || fileext =='jpeg' ){
                        if(jQuery.inArray(fileext, image_exts) > 0){

                            //var thumbnail   = file.thumbnails;
                            var thumbnailLink   = file.thumbnailLink;  //https://...?bounding_box=75&mode=fit
                            var thumbnail   = new Array();
                            thumbnail[1]    = thumbnailLink.replace('75', '256');
                            thumbnail[0]    = thumbnailLink.replace('75', '800');

                            var thumbnails  = new Array();


                            var j = 0;

                            for (var property in thumbnail) {
                                //console.log(j);
                                //console.log(thumbnail[property]);
                                //console.log(thumbnail);
                                thumbnails[j] = thumbnail[property];

                                if(j == 0){
                                    var filesingle1      = {} ;

                                    filesingle1.link     = thumbnails[j].replace('fit', 'crop');
                                    filesingle1.width    = '800px';
                                    filesingle1.height   = '800px';
                                    filesingle1.title    = 'Medium Size, Click to insert, right click to copy';
                                    filesingle1.text     = 'Medium Size(800px*800px)';
                                    filesingle1.dbicon     = icon;
                                    filesingle1.filetype     = 'image';

                                    filelist[1]         = filesingle1;  //original file
                                }
                                else if(j == 1){
                                    var filesingle2      = {};

                                    filesingle2.link     = thumbnails[j].replace('fit', 'crop');
                                    filesingle2.width    = '256px';
                                    filesingle2.height   = '256px';
                                    filesingle2.title    = 'Small Size, Click to insert, right click to copy';
                                    filesingle2.text     = 'Small Size(256px*256px)';
                                    filesingle2.dbicon     = icon;
                                    filesingle2.filetype     = 'image';

                                    filelist[2]         = filesingle2;  //original file
                                }
                                j++;
                            }//end loop


                            inputboxsinglewrap.prepend(
                                $("<img />", {
                                    'src'            : thumbnailLink.replace('fit', 'crop'),  //200*200
                                    'class'          : 'filelistwrapsingleimg',
                                    'title'          : 'Click to Insert image(75px * 75px )',
                                    'alt'            : 'thumb',
                                    'data-width'     : '75px',
                                    'data-height'    : '75px',
                                    'data-link'      : thumbnailLink.replace('fit', 'crop'),
                                    'data-dbicon'    : icon,
                                    'data-filetype'  : 'image'
                                })
                            );

                        }//end image check
                        //var filelistwrap = new Element("div",{ 'class': 'filelistwrap'});

                        //filelistwrap.inject(inputbox,'bottom');
                        //console.log(filelist.length);
                        for (var f = 0; f < filelist.length; f++) {
                            var ft = filelist[f];

                            var anchorlink = $("<a />",{
                                'class'         : 'filelistwrapsingle',
                                'title'         : ft.title,
                                'href'          : ft.link,
                                'text'          : ft.text,
                                'data-width'    : ft.width,
                                'data-height'   : ft.height,
                                'data-link'     : ft.link,
                                'data-filetype' : ft.filetype,
                                'data-dbicon'   : ft.icon
                            });
                            inputbox.append(anchorlink);

                            //add icon to first link if not image type

                            //console.log(ft.filetype);
                            //console.log(f);

                            if(f == 0 && ft.filetype == ''){
                                //anchorlink.before('<img class="dbfiletype" src="'+ft.icon+'" title="Icon" alt="icon" />');
                                anchorlink.html('<img class="dbfiletype" src="'+ft.icon+'" title="Icon" alt="icon" />'+anchorlink.html());
                            }


                        }//end file list




                    }//end for each file
                },
                linkType: 'direct',
                multiselect : 'true'


            });



            //document.getElementById('dropboxfilechooserbtn').appendChild(button);
            $('#dropboxfilechooserbtn').after(button);
            $('#dropboxfilechooserbtn').remove();
            $(button).addClass('button dropboxfilechooserbtn');

            //create an virtual wrapper div
            var cbdrpboxchooserwrap = $("<div />",{
                id:"cbdrpboxchooserwrap",
                'class':"cbdrpboxchooserwrap"

            });
            $("div#wp-content-editor-container").before(cbdrpboxchooserwrap);
            //create an element for note
            var codeboxrdroboxchoosernotice  = $("<div />", {
                id      : "codeboxrdroboxchoosernotice",
                'class' : "codeboxrdroboxchoosernotice"
                //text    : "Click on any image or link to insert into editor, right click for link"
            }).html('<span class="codeboxrdroboxchoosernoticel">Dropbox: Click to insert into editor, right click+copy for link</span><a href="#" data-status="open" id="codeboxrdroboxchoosernoticer" class="codeboxrdroboxchoosernoticer">-</a> ');
            //inject the dropbox button to the wrapper
            //codeboxrdroboxchoosernotice.inject(cbdrpboxchooserwrap, 'top');
            cbdrpboxchooserwrap.prepend(codeboxrdroboxchoosernotice);


            //create an element for textarea input for the links
            var dropboxinputwrap  = $("<div />", {
                //name: "dropboxbtntext",
                id:"dropboxinputwrap"
            });
            //dropboxinputwrap.inject(cbdrpboxchooserwrap, 'bottom');
            cbdrpboxchooserwrap.append(dropboxinputwrap);

            //insert clear div
            var dropboxclear  = $("<div/>", {
                'class': "cbclear"
            });
            //dropboxclear.inject(cbdrpboxchooserwrap, 'bottom');
            cbdrpboxchooserwrap.append(dropboxclear);

            $("a#codeboxrdroboxchoosernoticer").on( "click", function(evet) {
                evet.preventDefault();
                //console.log('clicked');

                var status = $(this).attr('data-status');
                if(status == '' || status == 'open'){
                    $("#dropboxinputwrap").slideUp();
                    $(this).attr('data-status', 'closed');
                    $(this).text('+');
                }
                else{
                    $("#dropboxinputwrap").slideDown();
                    $(this).attr('data-status', 'open');
                    $(this).text('-');
                }
            });

            $("#dropboxinputwrap").on( "click", 'a.filelistwrapsingle', function(evet) {
                //console.log(file.link);
                evet.preventDefault();

                var link        = $(this).attr('data-link');
                var width       = $(this).attr('data-width');
                var height      = $(this).attr('data-height');
                var filetype    = $(this).attr('data-filetype');
                var fileicon    = $(this).attr('data-dbicon');

                insertDBImgtoEditor( link, width, height, filetype, fileicon);
            });

            $("#dropboxinputwrap").on( "click", 'img.filelistwrapsingleimg', function(evet) {
                //console.log(file.link);
                evet.preventDefault();

                var link        = $(this).attr('data-link');
                var width       = $(this).attr('data-width');
                var height      = $(this).attr('data-height');
                var filetype    = $(this).attr('data-filetype');
                var fileicon    = $(this).attr('data-dbicon');

                insertDBImgtoEditor(link, width, height, filetype, fileicon);
            });


            $("#cbdrpboxchooserwrap").css({
               'display' : 'none'
            });

            //cbdrpboxchooserwrap.wrap('<div class="row-fluid">').wrap('<div class="span12">');

            //console.log(button);

        });

    });

}(jQuery));

