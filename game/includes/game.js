//on window ready
$(function() {
  //automatically focuses on any element which has an autofocus attribute present
  //only place this attribute on one element, such as <input autofocus />
  $("[autofocus]").focus(function() {
    if (this.setSelectionRange) {
      var len = this.value.length * 2;
      this.setSelectionRange(len, len);
    }
    else {
      this.value = this.value;
    }
    this.scrollTop = 999999;
  }).focus();

  var prompt = "> ";

  $("#button").click(function (evt)
  {
    //console.log(arguments);
    $("#commandLine").val(        //set #commandLine value to
      $("#commandHistory").val()  //#commandHistory value
        .trim()                   //without white space
        .split("\n")              //split into an array by new line
        .pop()                    //last item off the array
        .substring(prompt.length) //starting at the 3rd character to the end of the line
    );
  });

  //responds to keydown events on #commandHistory
  $("#commandHistory").keydown(function (evt) {
    //console.log(arguments);
    switch (evt.keyCode) {
      case 8:  //backspace key
        var commandHistory = $("#commandHistory").val();
        var lastTwoChars = commandHistory.substr(commandHistory.length - prompt.length);
        if (lastTwoChars == prompt) { //if last two chars match prompt
          evt.preventDefault();         //prevent event propagation
          evt.stopPropagation();        //prevent event propagation
          return false;
        }
        break;
      case 13: //enter
        $("#button").click();         //click the hidden button
        evt.preventDefault();         //prevent event propagation
        evt.stopPropagation();        //prevent event propagation
        return false;
        break;
      case 9:  //tab key
      case 46: //delete key
      case 37: //left arrow key
      case 38: //up arrow key
      case 39: //right arrow key
      case 40: //down arrow key
        evt.preventDefault();         //prevent event propagation
        evt.stopPropagation();        //prevent event propagation
        return false;
        break;
    }
  });
});
