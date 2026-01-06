<!--link rel="stylesheet" href="style.css"-->
<style>
/*@import url(https://fonts.googleapis.com/css?family=Gilda+Display);*/
html {
  background-color: white; /*black*/
  color: black;
  overflow: hidden;
  height: 100%;
  -webkit-user-select: none;
     -moz-user-select: none;
      -ms-user-select: none;
          user-select: none;
		  font-size: medium;
}

.error {
  text-align: center;
  font-family: 'Gilda Display', serif;
  
  text-align: center;
  width: 100%;
  height: 200px;
  margin: auto;
  position: absolute;
  top: 0;
  bottom: 0;
  left: -60px;
  right: 0;
  -webkit-animation: noise-3 1s linear infinite;
          animation: noise-3 1s linear infinite;
  overflow: default;
}

body:after {
  content: 'error 404';
  font-family: OCR-A;
  font-size: 130px;
  
  text-align: center;
  width: 550px;
  margin: auto;
  position: absolute;
  top: 25%;
  bottom: 0;
  left: 0;
  right: 35%;
  opacity: 0;
  color: red;
  -webkit-animation: noise-1 .2s linear infinite;
          animation: noise-1 .2s linear infinite;
}
body:before {
  content: 'error 404';
  font-family: OCR-A;
  font-size: 100px;
  
  text-align: center;
  width: 500px;
  margin: auto;
  position: absolute;
  top: 25%;
  bottom: 0;
  left: 0;
  right: 35%;
  opacity: 0;
  color: black;
  -webkit-animation: noise-2 .2s linear infinite;
          animation: noise-2 .2s linear infinite;
}

.info {
  text-align: center;
  width: 200px;
  height: 60px;
  margin: auto;
  position: absolute;
  top: 280px;
  bottom: 0;
  left: 20px;
  right: 0;
  -webkit-animation: noise-3 1s linear infinite;
          animation: noise-3 1s linear infinite;
}

.info:before {
  content: 'file not found';
  font-family: OCR-A;
  font-size: 90px;
  text-align: center;
  width: 800px; 
  margin: auto;
  position: absolute;
  top: 20px;
  bottom: 0;
  left: 40px;
  right: 100px;
  opacity: 0;
  color: black;
  -webkit-animation: noise-2 .2s linear infinite;
          animation: noise-2 .2s linear infinite;
}

.info:after {
  content: 'file not found';
  font-family: OCR-A;
  font-size: 100px;
  text-align: center;
  width: 800px;
  margin: auto;
  position: absolute;
  top: 20px;
  bottom: 0;
  left: 40px;
  right: 0;
  opacity: 0;
  color: white;
  -webkit-animation: noise-1 .2s linear infinite;
          animation: noise-1 .2s linear infinite;
}

@-webkit-keyframes noise-1 {
  0%, 20%, 40%, 60%, 70%, 90% {opacity: 0;}
  10% {opacity: .1;}
  50% {opacity: .5; left: -6px;}
  80% {opacity: .3;}
  100% {opacity: .6; left: 2px;}
}

@keyframes noise-1 {
  0%, 20%, 40%, 60%, 70%, 90% {opacity: 0;}
  10% {opacity: .1;}
  50% {opacity: .5; left: -6px;}
  80% {opacity: .3;}
  100% {opacity: .6; left: 2px;}
}

@-webkit-keyframes noise-2 {
  0%, 20%, 40%, 60%, 70%, 90% {opacity: 0;}
  10% {opacity: .1;}
  50% {opacity: .5; left: 6px;}
  80% {opacity: .3;}
  100% {opacity: .6; left: -2px;}
}

@keyframes noise-2 {
  0%, 20%, 40%, 60%, 70%, 90% {opacity: 0;}
  10% {opacity: .1;}
  50% {opacity: .5; left: 6px;}
  80% {opacity: .3;}
  100% {opacity: .6; left: -2px;}
}

@-webkit-keyframes noise {
  0%, 3%, 5%, 42%, 44%, 100% {opacity: 1; -webkit-transform: scaleY(1); transform: scaleY(1);}  
  4.3% {opacity: 1; -webkit-transform: scaleY(1.7); transform: scaleY(1.7);}
  43% {opacity: 1; -webkit-transform: scaleX(1.5); transform: scaleX(1.5);}
}

@keyframes noise {
  0%, 3%, 5%, 42%, 44%, 100% {opacity: 1; -webkit-transform: scaleY(1); transform: scaleY(1);}  
  4.3% {opacity: 1; -webkit-transform: scaleY(1.7); transform: scaleY(1.7);}
  43% {opacity: 1; -webkit-transform: scaleX(1.5); transform: scaleX(1.5);}
}

@-webkit-keyframes noise-3 {
  0%,3%,5%,42%,44%,100% {opacity: 1; -webkit-transform: scaleY(1); transform: scaleY(1);}
  4.3% {opacity: 1; -webkit-transform: scaleY(4); transform: scaleY(4);}
  43% {opacity: 1; -webkit-transform: scaleX(10) rotate(60deg); transform: scaleX(10) rotate(60deg);}
}

@keyframes noise-3 {
  0%,3%,5%,42%,44%,100% {opacity: 1; -webkit-transform: scaleY(1); transform: scaleY(1);}
  4.3% {opacity: 1; -webkit-transform: scaleY(4); transform: scaleY(4);}
  43% {opacity: 1; -webkit-transform: scaleX(10) rotate(60deg); transform: scaleX(10) rotate(60deg);}
}

.wrap {
  top: 30%;
  left: 25%;
  
  height: 200px;
  
  margin-top: -100px;
  position: absolute;
}
code {
  color: white;
}
span.blue {
  color: #48beef;
}
span.comment {
  color: #7f8c8d;
}
span.orange {
  color: #f39c12;
}
span.green {
  color: #33cc33;
}
span.red {
  color: red;
}

.viewFull {
  font-family:OCR-A;
  color:orange;
  text-decoration:;
}
@font-face {
    font-family: OCR-A;
} 

@media only screen and (min-height: 500px) {

.viewFull{
  display:none; 	
	}

}

.go_back {
  position: relative;
  top: 170px; left: 700px; 
  -webkit-animation: go_back 0.5s linear infinite;
  animation: go_back 0.5s linear infinite;
}

@-webkit-keyframes go_back {
  0% { color: rgba(34, 34, 34, 1); }
  50% { color: rgba(34, 34, 34, 0); }
  /*100% { color: rgba(34, 34, 34, 1); }*/
  100% { color: rgba(34, 255, 34, 1); }

}

@keyframes go_back {
  0% { color: rgba(34, 34, 34, 1); }
  50% { color: rgba(34, 34, 34, 0); }
  /*100% { color: rgba(34, 34, 34, 1); }*/
  100% { color: rgba(34, 255, 34, 1); }
}

</style>

<div style="go_back";>
  <h3><a href="http://localhost:8888" class="go_back"
      style="font-size: 25px;
        font-family: 'Consolas','Menlo', monospace;
        outline: none; text-decoration: none;"
        target="_parent"> ОБРАТНАЯ ТЯГА * </a></h3>
</div>

<div class="error">
  <div class="wrap">
    <div class="404">
      <pre><code>
	  <span class="green">&lt;!</span><span>DOCTYPE html</span><span class="green">&gt;</span>
      <span class="orange">&lt;html&gt;</span>
            <span class="orange">&lt;style&gt;</span>
      * {
  
<span class="green">everything</span>:<span class="blue">awesome</span>;
}
      <span class="orange">&lt;/style&gt;</span>
<span class="">&lt;body&gt;</span> 
                                    ERROR 404!
		FILE NOT FOUND!</h4>
            <span class="comment">

  &lt;!-- <span class="red">выбранного пути не существует</span>
                          the chosen path does not exist --&gt;</span>
      <span class="orange"></span> 
			  
      </div><br />
      <span class="info"><br />

      <span class="orange">&nbsp;&lt;/body&gt;</span><br/>
      <span class="orange">&lt;/html&gt;</span>
    </code></pre>
  </div>
</div></span>

<script>
  setTimeout(function() {
    window.location='http://localhost:8888'
  }, 3000);
</script>

