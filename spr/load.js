$(document).ready(function(){
    const divspin = document.querySelector('.spinner'); 
    const input = document.querySelector('#inp1');           
    $("form").on("submit", function(e){
        e.preventDefault();
        const statusMessage = document.createElement('img');
        statusMessage.src = 'img/form/spinner.svg';
        statusMessage.style.cssText = `
            display: block;
			align-items: center;
			`;
        divspin.append(statusMessage);  
        $.ajax({			
            url: '/weather2.php',
            method: 'post',
            dataType: 'html',
            data: {ORGNAME: input.value},
            success: function(data){
			document.querySelector(".load").innerHTML = '';	
            document.querySelector(".load").innerHTML = data; 
            statusMessage.remove();
            },
        });    
    });
});
