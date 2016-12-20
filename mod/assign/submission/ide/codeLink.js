var cMirror = CodeMirror(document.getElementById('IDE_spacer'),{
    lineNumbers:true
});
var string, scripts=[];
scripts.push(cMirror.getOption('mode'));
//put in default values according to language
alert(cMirror.getValue());
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
    alert('clicked');
    var text = cMirror.getValue();
    //call all compliation stuff
    //cMirror.setValue(compile(text));
});

//setup for language swapping
var select = document.getElementById('id_ide_lang');
if(select) {
    select.addEventListener('change', function (elem) {
        var dir = 'http://localhost/moodle/mod/assign/submission/ide/lib/CodeMirror/mode/';
        var mode = select.options[select.value].text;
        var exists = false;
        alert('changed to ' + mode);

        for(var i = 0;i<scripts.length;i++){
            alert(scripts[i] + ":" + mode);
            if(scripts[i]==mode){
                exists = true;
                break;
            }
        }

        if(!exists){
            var script = document.createElement('script');
            script.setAttribute('src', dir + mode + '/' + mode + '.js');
            document.getElementById('IDE_spacer').appendChild(script);
            scripts.push(mode);
            cMirror.setOption('mode', mode);
            cMirror.reload();
        }else {
            cMirror.setOption('mode', mode);
            cMirror.reload();
        }
    });
}




