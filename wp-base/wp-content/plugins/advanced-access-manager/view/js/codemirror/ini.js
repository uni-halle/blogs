CodeMirror.defineMode("ini", function() {
  return {
    token: function(stream, state) {
		
		var ch = stream.next();
		
		state.value = (stream.sol() ? false : state.value);
	
		if (ch == "["){
			state.section = true;
			return "section";
		}
	  
		if (ch == ']'){
			state.section = false;
			return "section";
		}

	  	if (state.section){
			return "section";
		}

		if (ch == ";"){
			stream.skipToEnd();
			return "comment";
		}
		
		if (ch == '"'){
			state.double_quote = (state.double_quote == true ? false : true);
			return "string";
		}
		
		if (state.double_quote){
			return "string";
		}
    },
	startState: function() {
			return {
				section: false,
				double_quote : false,
			};
		}
  };
});

CodeMirror.defineMIME("text/plane", "ini");