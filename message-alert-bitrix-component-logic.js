function messageAlert()
{
    var messageAlert_hours      = new Date().getHours();
    var messageAlert_minutes    = new Date().getMinutes();
    var cookie                  = document.cookie;

    // message alert ON_1
    var findPattern = "USER_MAIN_DEPARTMENT=";// set pattern for search data in current cookie

    if (cookie.indexOf(findPattern) != -1)
    {
        var messageAlert_department = cookie.slice((cookie.indexOf(findPattern) + findPattern.length),
                                         (cookie.indexOf(";", (cookie.indexOf(findPattern) + findPattern.length))));
        if (messageAlert_hours == 17 && messageAlert_minutes == 15 && messageAlert_department == 215)//set hours,minutes, and department ID
        {
            var Dialog = new BX.CDialog({
                                title    : '!!! ВНИМАНИЕ !!!',
                                head     : 'Отчет по делам в CRM',
                                content  : 'Через 15 минут будет собран отчет по делам в CRM,\
                                            просьба завершить все невыполненные дела, или при невозможности\
                                            их выполнения, перенесите их на дальнейший срок.',
                                resizable: false,
                                height   : '200',
                                width    : '400'});
            Dialog.SetButtons([
            {
                'title': 'Принято',
                'id': 'accept',
                'name': 'accept',
                'action': function(){
                    this.parentWindow.Close();
                }
            }
            ]);
            Dialog.Show();
        }

    }
    // message alert OFF_1
    
    // message alert ON_2
        var findPattern = "USER_MAIN_DEPARTMENT=";// set pattern for search data in current cookie

    if (cookie.indexOf(findPattern) != -1)
    {
        var messageAlert_department = cookie.slice((cookie.indexOf(findPattern) + findPattern.length),
                                         (cookie.indexOf(";", (cookie.indexOf(findPattern) + findPattern.length))));
        if (messageAlert_hours == 17 && messageAlert_minutes == 15 && messageAlert_department == 13)//set hours,minutes, and department ID
        {
            var Dialog = new BX.CDialog({
                                title    : '!!! ВНИМАНИЕ !!!',
                                head     : 'Отчет по делам в сделках направления - Мониторинг',
                                content  : 'Через 15 минут будет собран отчет по делам в\
                                            сделках по направлению - Мониторинг, просьба\
                                            актуализировать свои дела и их сроки по клиентам\
                                            в сделках.',
                                resizable: false,
                                height   : '200',
                                width    : '400'});
            Dialog.SetButtons([
            {
                'title': 'Принято',
                'id': 'accept',
                'name': 'accept',
                'action': function(){
                    this.parentWindow.Close();
                }
            }
            ]);
            Dialog.Show();
        }

    }
    // message alert OFF_2
    

    // message alert ON_3
    // ... some code like ON_1 / OFF_1
    // message alert OFF_3

    // message alert ON_4
    // ... some code like ON_1 / OFF_1
    // message alert OFF_4

    // message alert ON_5
    // ... some code like ON_1 / OFF_1
    // message alert OFF_5
    
    // etc ...
}

setInterval(messageAlert, 33333);