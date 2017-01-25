var cMirror = CodeMirror(document.getElementById('IDE_spacer'),{
    lineNumbers:true
});
var string, scripts=[];
var request;
CodeMirror.modeURL='http://localhost/moodle/mod/assign/submission/ide/lib/CodeMirror/mode/%N/%N.js';
scripts.push(cMirror.getOption('mode'));
//put in default values according to language
if(cMirror.getValue() == '') {
    switch (cMirror.getOption('mode')) {
        case 'javascript':
        case 'java':
        case 'php':
            string = '//Your Code Here\n\n';
            break;
        case 'html':
            string = '<!--Your Code Here\n\n';
            break;
        default:
            string = '//Your Code Here\n\n';
            break;
    }
    cMirror.setValue(string);
}


//setup for run button
var run = document.getElementById('id_ide_run');
run.addEventListener('click', function(elem){
    relog();
    var text = cMirror.getValue();
    //call all compliation stuff
    cMirror.setValue("");
    var lang = cMirror.getOption('mode');
    if (lang !== 'javascript') {
        request = $.ajax({url:'compile.php',
            type:'post',
            data: {text:text, lang:lang},
            dataType:'json',
            timeout:3000});
        request.done(function (result){
            eval(result);
        });
    }
    else {
        eval(text);
    }
});

//setup for language swapping
var select = document.getElementById('id_ide_lang');
if(select) {
    select.addEventListener('change', function (elem) {
        var dir = 'http://localhost/moodle/mod/assign/submission/ide/lib/CodeMirror/mode/';
        var mode = select.options[select.value].text;
        var exists = false;

        for(var i = 0;i<scripts.length;i++){
            if(scripts[i]==mode){
                exists = true;
                break;
            }
        }
        if(!exists){
            //var spacer = document.getElementById('IDE_spacer');
            //var script = document.createElement('script');
            //script.setAttribute('src', dir + mode + '/' + mode + '.js');
            //spacer.insertBefore(script, spacer.firstChild);
            alert('changing mode');
            cMirror.setOption('mode', mode);
            CodeMirror.autoLoadMode(cMirror, mode);
            scripts.push(mode);
            alert('mode now set to ' +cMirror.getOption('mode'));
        }else {
            cMirror.setOption('mode', mode);
        }
    });
}

function relog() {
    window.console.log = function (msg) {
        cMirror.setValue(cMirror.getValue() + msg);
    };
    window.console.warn = function (msg) {
        cMirror.setValue(cMirror.getValue() + msg);
    };
    window.console.info = function (msg) {
        cMirror.setValue(cMirror.getValue() + msg);
    };
    window.console.debug = function (msg) {
        cMirror.setValue(cMirror.getValue() + msg);
    }
}






