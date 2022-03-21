<!DOCTYPE html>

<html>
    <head>
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Prompt&display=swap" rel="stylesheet">
        
        <style>

            body {
                font-family: 'Prompt', sans-serif;
            }
			#myVideo {
			  position: fixed;
			  right: 0;
			  bottom: 0;
			  min-width: 100%;
			  min-height: 100%;
			}
			.fade-in {
				opacity: 1;
				animation-name: fadeInOpacity;
				animation-iteration-count: 1;
				animation-timing-function: ease-in;
				animation-duration: 2s;
			}

			@keyframes fadeInOpacity {
				0% {
					opacity: 0;
				}
				100% {
					opacity: 1;
				}
			}
			.fade-out {
				opacity: 0;
				animation-name: fadeOutOpacity;
				animation-iteration-count: 1;
				animation-timing-function: ease-in;
				animation-duration: 2s;
			}

			@keyframes fadeOutOpacity {
				0% {
					opacity: 1;
				}
				100% {
					opacity: 0;
					visibility: hidden;
				}
			}

        </style>
        
        
    </head>
	<script>
	addEventListener("click", function() {
    var
          el = document.documentElement
        , rfs =
               el.requestFullScreen
            || el.webkitRequestFullScreen
            || el.mozRequestFullScreen
    ;
    rfs.call(el);
	var audio = document.getElementById("audio");
    audio.play();
	});
	var myaudio = document.getElementById("audioID").autoplay = true;
</script>
<body  style="height: 100vh"> 
	<video autoplay muted loop id="myVideo" style="opacity: 0.9;">
	  <source src="/public/sky_bg.mp4" type="video/mp4">
	</video>
	<!--<audio id="player" loop="" preload="auto" src="/public/bgm.mp3" id = "audio">
		<source src="/public/bgm.mp3">Your browser does not support the <code>audio</code> element.</audio>-->
    <div class="container" ng-controller="moduleAppController">

   <audio hidden controls loop id="audio">
		<source src="/public/bgm.mp3" type="audio/mpeg">
		<source src="/public/bgm.mp3" type="audio/mp3">
		Your browser does not support the audio element.
	</audio>
        <div class="row justify-content-center">
			
            <div class="col-10 fade-in" id="0" style="height: 100vh; color: white;font-size: 3vh;text-shadow: 2px 2px 6px #000000; display: flex; align-items: center; text-align: center;">
				<p></br>
                “ทุกประสบการณ์ที่ผ่านมา
				จะสอนให้เราได้เติบโตขึ้น”</br></br>
				เราอยากให้คุณใช้เวลา 10 นาที
				ให้ผ่านไปอย่างช้า ๆ ได้คิดและทบทวน
				สิ่งต่าง ๆ ที่คุณได้ผ่านมา
				และขอบคุณตัวเอง
				ที่วันนี้ได้เติบโตขึ้นไปอีกขั้น</br></br>
				<button type="button" id="0b" class="btn btn-outline-light btn-lg" ng-click="next()" style="font-size: 2.5vh">ไปต่อ</button>
				</p>
            </div>

			<div class="col-10 fade-in ng-hide" id="1" style="height: 100vh; color: white;font-size: 3vh;text-shadow: 2px 2px 6px #000000; align-items: center; text-align: center; padding-top: 40vh">
				</br>
				สวัสดี 
				</br>
				{{employeeDetail.employee_nickname_thai}}
				</br></br>
				<button type="button" id="1b" class="btn btn-outline-light btn-lg" ng-click="next()" style="font-size: 2.5vh">ไปต่อ</button>
            </div>
			
			<div class="col-10 fade-in ng-hide" id="2" style="height: 100vh; color: white;font-size: 3vh;text-shadow: 2px 2px 6px #000000; align-items: center; text-align: center; padding-top: 40vh">
				</br>
				เป็นยังไงบ้าง</br>ช่วงนี้เหนื่อยมั้ย?
				</br></br>
				<button type="button" id="2b" class="btn btn-outline-light btn-lg" ng-click="next()" style="font-size: 2.5vh">ไปต่อ</button>
            </div>

			<div class="col-10 fade-in ng-hide" id="3" style="height: 100vh; color: white;font-size: 3vh;text-shadow: 2px 2px 6px #000000; align-items: center; text-align: center; padding-top: 40vh">
				</br>
				...
				</br></br>
				<button type="button" id="3b" class="btn btn-outline-light btn-lg" ng-click="next()" style="font-size: 2.5vh">ไปต่อ</button>
            </div>

			<div class="col-10 fade-in ng-hide" id="4" style="height: 100vh; color: white;font-size: 3vh;text-shadow: 2px 2px 6px #000000; align-items: center; text-align: center; padding-top: 40vh">
				</br>
				มันก็ผ่านมาเกือบ 3 เดือนแล้วนะ
				</br></br>
				<button type="button" id="4b" class="btn btn-outline-light btn-lg" ng-click="next()" style="font-size: 2.5vh">ไปต่อ</button>
            </div>

			<div class="col-10 fade-in ng-hide" id="5" style="height: 100vh; color: white;font-size: 3vh;text-shadow: 2px 2px 6px #000000; align-items: center; text-align: center; padding-top: 40vh">
				</br>
				ที่ {{employeeDetail.employee_nickname_thai}} เข้ามาเป็นส่วนหนึ่งของ CBA
				</br></br>
				<button type="button" id="5b" class="btn btn-outline-light btn-lg" ng-click="next()" style="font-size: 2.5vh">ไปต่อ</button>
            </div>

			<div class="col-10 fade-in ng-hide" id="6" style="height: 100vh; color: white;font-size: 3vh;text-shadow: 2px 2px 6px #000000; align-items: center; text-align: center; padding-top: 40vh">
				</br>ยังจำได้ไหม</br>ว่าทำไม {{employeeDetail.employee_nickname_thai}} ถึงมาทำตรงนี้?
				</br></br>
				<button type="button" id="6b" class="btn btn-outline-light btn-lg" ng-click="next()" style="font-size: 2.5vh">ไปต่อ</button>
            </div>

			<div class="col-10 fade-in ng-hide" id="7" style="height: 100vh; color: white;font-size: 3vh;text-shadow: 2px 2px 6px #000000; align-items: center; text-align: center; padding-top: 40vh">
				</br>เพราะเพื่อน?
				</br></br>
				<button type="button" id="7b" class="btn btn-outline-light btn-lg" ng-click="next()" style="font-size: 2.5vh">ไปต่อ</button>
            </div>

			<div class="col-10 fade-in ng-hide" id="8" style="height: 100vh; color: white;font-size: 3vh;text-shadow: 2px 2px 6px #000000; align-items: center; text-align: center; padding-top: 40vh">
				</br>เพราะพี่?
				</br></br>
				<button type="button" id="8b" class="btn btn-outline-light btn-lg" ng-click="next()" style="font-size: 2.5vh">ไปต่อ</button>
            </div>

			<div class="col-10 fade-in ng-hide" id="9" style="height: 100vh; color: white;font-size: 3vh;text-shadow: 2px 2px 6px #000000; align-items: center; text-align: center; padding-top: 40vh">
				</br>เพราะอยากพัฒนาตัวเอง?
				</br></br>
				<button type="button" id="9b" class="btn btn-outline-light btn-lg" ng-click="next()" style="font-size: 2.5vh">ไปต่อ</button>
            </div>

			<div class="col-10 fade-in ng-hide" id="10" style="height: 100vh; color: white;font-size: 3vh;text-shadow: 2px 2px 6px #000000; align-items: center; text-align: center; padding-top: 40vh">
				</br>เพราะ…</br></br>
				<input class="form-control form-control-lg" type="text" id="reason">
				</br></br>
				<button type="button" id="10b" class="btn btn-outline-light btn-lg" ng-click="next()" style="font-size: 2.5vh">ไปต่อ</button>
            </div>

			<div class="col-10 fade-in ng-hide" id="11" style="height: 100vh; color: white;font-size: 3vh;text-shadow: 2px 2px 6px #000000; align-items: center; text-align: center; padding-top: 40vh">
				</br>{{employeeDetail.employee_nickname_thai}} ได้เจอสิ่งที่คาดหวังแล้วหรือยัง?</br></br>
				</br></br>
				<button type="button" id="11bt" class="btn btn-outline-light btn-block" ng-click="next_11(true)" style="font-size: 2.5vh">เจอแล้ว</button>
				</br></br>
				<button type="button" id="11bf" class="btn btn-outline-light btn-block" ng-click="next_11(false)" style="font-size: 2.5vh">ยังไม่เจอ</button>
            </div>

			<div class="col-10 fade-in ng-hide" id="12" style="height: 100vh; color: white;font-size: 3vh;text-shadow: 2px 2px 6px #000000; align-items: center; text-align: center; padding-top: 40vh">
				</br>ยินดีด้วยนะ</br></br>
				<button type="button" id="12b" class="btn btn-outline-light btn-lg" ng-click="next()" style="font-size: 2.5vh">ไปต่อ</button>
            </div>

			<div class="col-10 fade-in ng-hide" id="13" style="height: 100vh; color: white;font-size: 3vh;text-shadow: 2px 2px 6px #000000; align-items: center; text-align: center; padding-top: 40vh">
				</br>ที่ได้เจอในสิ่งที่หวังไว้ :)</br></br>
				<button type="button" id="13b" class="btn btn-outline-light btn-lg" ng-click="next()" style="font-size: 2.5vh">ไปต่อ</button>
            </div>

			<div class="col-10 fade-in ng-hide" id="14" style="height: 100vh; color: white;font-size: 3vh;text-shadow: 2px 2px 6px #000000; align-items: center; text-align: center; padding-top: 40vh">
				</br>{{employeeDetail.employee_nickname_thai}} มีอะไรที่อยากทำ</br>แล้วยังไม่ได้ทำอีกหรือเปล่า?</br></br>
				<button type="button" id="14b" class="btn btn-outline-light btn-lg" ng-click="next()" style="font-size: 2.5vh">ไปต่อ</button>
            </div>

			<div class="col-10 fade-in ng-hide" id="15" style="height: 100vh; color: white;font-size: 3vh;text-shadow: 2px 2px 6px #000000; align-items: center; text-align: center; padding-top: 40vh">
				</br>สิ่งนั้นคืออะไรหรอ…</br></br>
				<input class="form-control form-control-lg" type="text" id="what1">
				</br></br>
				<button type="button" id="15b" class="btn btn-outline-light btn-lg" ng-click="next_19()" style="font-size: 2.5vh">ไปต่อ</button>
            </div>

			<div class="col-10 fade-in ng-hide" id="16" style="height: 100vh; color: white;font-size: 3vh;text-shadow: 2px 2px 6px #000000; align-items: center; text-align: center; padding-top: 40vh">
				</br>ทำไม {{employeeDetail.employee_nickname_thai}} คิดว่ายังไม่เจอ?</br></br>
				<button type="button" id="16b" class="btn btn-outline-light btn-lg" ng-click="next()" style="font-size: 2.5vh">ไปต่อ</button>
            </div>

			<div class="col-10 fade-in ng-hide" id="17" style="height: 100vh; color: white;font-size: 3vh;text-shadow: 2px 2px 6px #000000; align-items: center; text-align: center; padding-top: 40vh">
				</br>ถ้าย้อนเวลากลับไปได้</br></br>
				<button type="button" id="17b" class="btn btn-outline-light btn-lg" ng-click="next()" style="font-size: 2.5vh">ไปต่อ</button>
            </div>

			<div class="col-10 fade-in ng-hide" id="18" style="height: 100vh; color: white;font-size: 3vh;text-shadow: 2px 2px 6px #000000; align-items: center; text-align: center; padding-top: 40vh">
				</br>{{employeeDetail.employee_nickname_thai}} จะทำอะไรที่แตกต่าง
				</br>ไปจากเดิมไหม?</br></br>
				<button type="button" id="18b" class="btn btn-outline-light btn-lg" ng-click="next()" style="font-size: 2.5vh">ไปต่อ</button>
            </div>

			<div class="col-10 fade-in ng-hide" id="19" style="height: 100vh; color: white;font-size: 3vh;text-shadow: 2px 2px 6px #000000; align-items: center; text-align: center; padding-top: 40vh">
				</br>สิ่งนั้นคืออะไรหรอ…</br></br>
				<input class="form-control form-control-lg" type="text" id="what2">
				</br></br>
				<button type="button" id="19b" class="btn btn-outline-light btn-lg" ng-click="next_19()" style="font-size: 2.5vh">ไปต่อ</button>
            </div>

			<div class="col-10 fade-in ng-hide" id="20" style="height: 100vh; color: white;font-size: 3vh;text-shadow: 2px 2px 6px #000000; align-items: center; text-align: center; padding-top: 40vh">
				</br>แล้ว…</br></br>
				<button type="button" id="20b" class="btn btn-outline-light btn-lg" ng-click="next()" style="font-size: 2.5vh">ไปต่อ</button>
            </div>

			<div class="col-10 fade-in ng-hide" id="21" style="height: 100vh; color: white;font-size: 3vh;text-shadow: 2px 2px 6px #000000; align-items: center; text-align: center; padding-top: 40vh">
				</br>ถ้าลองตัดความคาดหวัง
				</br>เหล่านั้นออกไปล่ะ</br></br>
				<button type="button" id="21b" class="btn btn-outline-light btn-lg" ng-click="next()" style="font-size: 2.5vh">ไปต่อ</button>
            </div>

			<div class="col-10 fade-in ng-hide" id="22" style="height: 100vh; color: white;font-size: 3vh;text-shadow: 2px 2px 6px #000000; align-items: center; text-align: center; padding-top: 40vh">
				</br>{{employeeDetail.employee_nickname_thai}} คิดว่าที่ผ่านมา</br></br>
				<button type="button" id="22b" class="btn btn-outline-light btn-lg" ng-click="next()" style="font-size: 2.5vh">ไปต่อ</button>
            </div>

			<div class="col-10 fade-in ng-hide" id="23" style="height: 100vh; color: white;font-size: 3vh;text-shadow: 2px 2px 6px #000000; align-items: center; text-align: center; padding-top: 40vh">
				</br>ปัญหาที่หนักที่สุดที่ได้เจอ
				</br>คืออะไรหรอ….</br></br>
				<input class="form-control form-control-lg" type="text" id="problem"></br></br>
				<button type="button" id="23b" class="btn btn-outline-light btn-lg" ng-click="next()" style="font-size: 2.5vh">ไปต่อ</button>
            </div>

			<div class="col-10 fade-in ng-hide" id="24" style="height: 100vh; color: white;font-size: 3vh;text-shadow: 2px 2px 6px #000000; align-items: center; text-align: center; padding-top: 40vh">
				</br>แล้ว {{employeeDetail.employee_nickname_thai}} ได้เรียนรู้อะไร
				</br>จากปัญหาเหล่านั้นบ้าง?</br></br>
				<input class="form-control form-control-lg" type="text" id="solution"></br></br>
				<button type="button" id="24b" class="btn btn-outline-light btn-lg" ng-click="next()" style="font-size: 2.5vh">ไปต่อ</button>
            </div>

			<div class="col-10 fade-in ng-hide" id="25" style="height: 100vh; color: white;font-size: 3vh;text-shadow: 2px 2px 6px #000000; align-items: center; text-align: center; padding-top: 40vh">
				</br>ไม่เป็นไรนะคนเก่ง</br></br>
				<button type="button" id="25b" class="btn btn-outline-light btn-lg" ng-click="next()" style="font-size: 2.5vh">ไปต่อ</button>
            </div>

			<div class="col-10 fade-in ng-hide" id="26" style="height: 100vh; color: white;font-size: 3vh;text-shadow: 2px 2px 6px #000000; align-items: center; text-align: center; padding-top: 40vh">
				</br>{{employeeDetail.employee_nickname_thai}} ผ่านมันมาได้แล้ว</br></br>
				<button type="button" id="26b" class="btn btn-outline-light btn-lg" ng-click="next()" style="font-size: 2.5vh">ไปต่อ</button>
            </div>

			<div class="col-10 fade-in ng-hide" id="27" style="height: 100vh; color: white;font-size: 3vh;text-shadow: 2px 2px 6px #000000; align-items: center; text-align: center; padding-top: 40vh">
				</br>ดีใจนะที่ได้เห็น {{employeeDetail.employee_nickname_thai}} เติบโต</br></br>
				<button type="button" id="27b" class="btn btn-outline-light btn-lg" ng-click="next()" style="font-size: 2.5vh">ไปต่อ</button>
            </div>

			<div class="col-10 fade-in ng-hide" id="28" style="height: 100vh; color: white;font-size: 3vh;text-shadow: 2px 2px 6px #000000; align-items: center; text-align: center; padding-top: 40vh">
				</br>รู้ไหมว่ามีใครบางคน</br></br>
				<button type="button" id="28b" class="btn btn-outline-light btn-lg" ng-click="next()" style="font-size: 2.5vh">ไปต่อ</button>
            </div>

			<div class="col-10 fade-in ng-hide" id="29" style="height: 100vh; color: white;font-size: 3vh;text-shadow: 2px 2px 6px #000000; align-items: center; text-align: center; padding-top: 40vh">
				</br>ก็ดีใจกับ {{employeeDetail.employee_nickname_thai}} เหมือนกันนะ :)</br></br>
				<button type="button" id="29b" class="btn btn-outline-light btn-lg" ng-click="next()" style="font-size: 2.5vh">ไปต่อ</button>
            </div>

			<div class="col-10 fade-in ng-hide" id="30" style="height: 100vh; color: white;font-size: 3vh;text-shadow: 2px 2px 6px #000000; align-items: center; text-align: center; padding-top: 40vh">
				</br>...</br></br>
				<button type="button" id="30b" class="btn btn-outline-light btn-lg" ng-click="next()" style="font-size: 2.5vh">ไปต่อ</button>
            </div>

			<div class="col-10 fade-in ng-hide" id="31" style="height: 100vh; color: white;font-size: 3vh;text-shadow: 2px 2px 6px #000000; align-items: center; text-align: center; padding-top: 40vh">
				</br>“พรุ่งนี้มีประชุม 9 โมงนะครับ”</br></br>
				<button type="button" id="31b" class="btn btn-outline-light btn-lg" ng-click="next()" style="font-size: 2.5vh">ไปต่อ</button>
            </div>

			<div class="col-10 fade-in ng-hide" id="32" style="height: 100vh; color: white;font-size: 3vh;text-shadow: 2px 2px 6px #000000; align-items: center; text-align: center; padding-top: 40vh">
				</br>“ฝากตามเพื่อน ๆ ด้วยนะครับ”</br></br>
				<button type="button" id="32b" class="btn btn-outline-light btn-lg" ng-click="next()" style="font-size: 2.5vh">ไปต่อ</button>
            </div>

			<div class="col-10 fade-in ng-hide" id="33" style="height: 100vh; color: white;font-size: 3vh;text-shadow: 2px 2px 6px #000000; align-items: center; text-align: center; padding-top: 40vh">
				</br>“วันนี้ปิดระบบ 5 โมงนะคะ”</br></br>
				<button type="button" id="33b" class="btn btn-outline-light btn-lg" ng-click="next()" style="font-size: 2.5vh">ไปต่อ</button>
            </div>

			<div class="col-10 fade-in ng-hide" id="34" style="height: 100vh; color: white;font-size: 3vh;text-shadow: 2px 2px 6px #000000; align-items: center; text-align: center; padding-top: 40vh">
				</br>“อีกนิดเดียวนะครับทุกคน
				</br>สู้ ๆ นะครับทุกคน”
				</br></br>
				<button type="button" id="34b" class="btn btn-outline-light btn-lg" ng-click="next()" style="font-size: 2.5vh">ไปต่อ</button>
            </div>

			<div class="col-10 fade-in ng-hide" id="35" style="height: 100vh; color: white;font-size: 3vh;text-shadow: 2px 2px 6px #000000; align-items: center; text-align: center; padding-top: 40vh">
				</br>...</br></br>
				<button type="button" id="35b" class="btn btn-outline-light btn-lg" ng-click="next()" style="font-size: 2.5vh">ไปต่อ</button>
            </div>

			<div class="col-10 fade-in ng-hide" id="36" style="height: 100vh; color: white;font-size: 3vh;text-shadow: 2px 2px 6px #000000; align-items: center; text-align: center; padding-top: 40vh">
				</br>ประโยคเหล่านี้มันคุ้น ๆ มั้ย?</br></br>
				<button type="button" id="36b" class="btn btn-outline-light btn-lg" ng-click="next()" style="font-size: 2.5vh">ไปต่อ</button>
            </div>

			<div class="col-10 fade-in ng-hide" id="37" style="height: 100vh; color: white;font-size: 3vh;text-shadow: 2px 2px 6px #000000; align-items: center; text-align: center; padding-top: 40vh">
				</br>พรุ่งนี้คงไม่มีอีกแล้วนะ</br></br>
				<button type="button" id="37b" class="btn btn-outline-light btn-lg" ng-click="next()" style="font-size: 2.5vh">ไปต่อ</button>
            </div>

			<div class="col-10 fade-in ng-hide" id="38" style="height: 100vh; color: white;font-size: 3vh;text-shadow: 2px 2px 6px #000000; align-items: center; text-align: center; padding-top: 40vh">
				</br>ก่อนจะจากกันไป...</br></br>
				<button type="button" id="38b" class="btn btn-outline-light btn-lg" ng-click="next()" style="font-size: 2.5vh">ไปต่อ</button>
            </div>

			<div class="col-10 fade-in ng-hide" id="39" style="height: 100vh; color: white;font-size: 3vh;text-shadow: 2px 2px 6px #000000; align-items: center; text-align: center; padding-top: 40vh">
				</br>มีใครบางคนอยากจะบอก
				</br>อะไรบางอย่างกับ {{employeeDetail.employee_nickname_thai}}</br></br>
				<button type="button" id="39b" class="btn btn-outline-light btn-lg" ng-click="next()" style="font-size: 2.5vh">ไปต่อ</button>
            </div>

			<div class="col-10 fade-in ng-hide" id="40" style="height: 100vh; color: white;font-size: 3vh;text-shadow: 2px 2px 6px #000000; align-items: center; text-align: center; padding-top: 40vh">
				</br>พอจะเดาออกมั้ย
				</br>ว่าเขาคนนั้นคือใคร?
				</br></br>
				<button type="button" id="40b" class="btn btn-outline-light btn-lg" ng-click="postAnswer()" style="font-size: 2.5vh">ไปต่อ</button>
            </div>

			<div class="col-10 fade-in ng-hide" id="41" style="height: 100vh; color: white;font-size: 3vh;text-shadow: 2px 2px 6px #000000; align-items: center; text-align: center">
				<div ng-show="employeeDetail.position=='CE'">
					<img class="card-img-top rounded" style="width: auto; max-height: 350px; text-align: center; display: flex;" ng-src="/public/MKT/CM{{employeeDetail.product_line}}.png">
					
				</div>
				<div ng-show="employeeDetail.position=='CM'">
					<img class="card-img-top rounded" style="width: auto; max-height: 350px; text-align: center; display: flex;" ng-src="/public/SMD.png">
					</br></br>
					<img class="card-img-top rounded" style="width: auto; max-height: 350px; text-align: center; display: flex;" ng-src="/public/GM.png">
				</div>
				<div ng-show="employeeDetail.position!='CM'&&employeeDetail.position!='CE'">
					<div ng-show="num=='00'||num=='01'||num=='02'">
						<img class="card-img-top rounded" style="width: auto; max-height: 350px; text-align: center; display: flex;" ng-src="/public/SMD.png">
						</br></br>
						<img class="card-img-top rounded" style="width: auto; max-height: 350px; text-align: center; display: flex;" ng-src="/public/GM.png">
					</div>
					<div ng-show="num!='00'&&num!='01'&&num!='02'">
						<img class="card-img-top rounded" style="width: auto; max-height: 350px; text-align: center; display: flex;" ng-src="/public/SYSTEM/{{employeeDetail.position}}_D.png">
						</br></br>
						<img class="card-img-top rounded" style="width: auto; max-height: 350px; text-align: center; display: flex;" ng-src="/public/SYSTEM/{{employeeDetail.position}}_M.png">
					</div>

				</div>
            </div>
        </div>

		
            
    </div>

</body>
</html>

<script>

    app.controller('moduleAppController', function($scope, $http, $compile) {
		
        $http.get("/home/employeeDetail").then(function(response) {
            $scope.employeeDetail = response.data;
			$scope.num=response.data.employee_id.substring(response.data.employee_id.length - 2);
        });
        $scope.stage=0;
		$scope.next = function() {
			$scope.target_stage=$scope.stage;
			document.getElementById($scope.target_stage+'b').disabled = true;
			document.getElementById($scope.target_stage).classList.remove('fade-in');
			document.getElementById($scope.target_stage).classList.add('fade-out');  
			$scope.stage+=1;
			if($scope.target_stage==10){
				$scope.reason=document.getElementById("reason").value;
			}
			if($scope.target_stage==23){
				$scope.problem=document.getElementById("problem").value;
			}
			if($scope.target_stage==24){
				$scope.solution=document.getElementById("solution").value;
			}
			setTimeout(function(){
				document.getElementById($scope.target_stage).innerHTML
                = '';
				document.getElementById($scope.target_stage).classList.add('ng-hide');
				document.getElementById($scope.target_stage).classList.remove('col-10');
				document.getElementById($scope.target_stage).attributeStyleMap.clear();
				document.getElementById($scope.stage).classList.remove('ng-hide');
				document.getElementById($scope.stage).classList.remove('fade-out');
				document.getElementById($scope.stage).classList.add('fade-in');  
		   },2000);
			
        }
		$scope.next_11 = function(x) {
			$scope.found=x;
			$scope.target_stage=$scope.stage;
			document.getElementById($scope.target_stage+'bt').disabled = true;
			document.getElementById($scope.target_stage+'bf').disabled = true;
			document.getElementById($scope.target_stage).classList.remove('fade-in');
			document.getElementById($scope.target_stage).classList.add('fade-out');  
			if(x==true){
				$scope.stage+=1;
			} else if (x==false){
				$scope.stage=16;   
			}
			setTimeout(function(){
				document.getElementById($scope.target_stage).innerHTML
                = '';
				document.getElementById($scope.target_stage).classList.add('ng-hide');
				document.getElementById($scope.target_stage).classList.remove('col-10');
				document.getElementById($scope.target_stage).attributeStyleMap.clear();
				document.getElementById($scope.stage).classList.remove('ng-hide');
				document.getElementById($scope.stage).classList.remove('fade-out');
				document.getElementById($scope.stage).classList.add('fade-in');  
		   },2000);
			
        }
		$scope.next_19 = function() {
			$scope.target_stage=$scope.stage;
			if($scope.found==true){
			   $scope.what=document.getElementById("what1").value;
			} else if($scope.found==false){
				$scope.what=document.getElementById("what2").value;
			}
			document.getElementById($scope.target_stage+'b').disabled = true;
			document.getElementById($scope.target_stage).classList.remove('fade-in');
			document.getElementById($scope.target_stage).classList.add('fade-out');  
			$scope.stage=20;
			setTimeout(function(){
				document.getElementById($scope.target_stage).innerHTML
                = '';
				document.getElementById($scope.target_stage).classList.add('ng-hide');
				document.getElementById($scope.target_stage).classList.remove('col-10');
				document.getElementById($scope.target_stage).attributeStyleMap.clear();
				document.getElementById($scope.stage).classList.remove('ng-hide');
				document.getElementById($scope.stage).classList.remove('fade-out');
				document.getElementById($scope.stage).classList.add('fade-in');  
		   },2000);
			
        }
        
        $scope.postAnswer = function() {
			$scope.target_stage=$scope.stage;
			document.getElementById($scope.target_stage+'b').disabled = true;
			document.getElementById($scope.target_stage).classList.remove('fade-in');
			document.getElementById($scope.target_stage).classList.add('fade-out');  
			$scope.stage+=1;
			setTimeout(function(){
				document.getElementById($scope.target_stage).innerHTML
                = '';
				document.getElementById($scope.target_stage).classList.add('ng-hide');
				document.getElementById($scope.target_stage).classList.remove('col-10');
				document.getElementById($scope.target_stage).attributeStyleMap.clear();
				document.getElementById($scope.stage).classList.remove('ng-hide');
				document.getElementById($scope.stage).classList.remove('fade-out');
				document.getElementById($scope.stage).classList.add('fade-in');  
		   },2000);
			$.post('/is/postEndProject', {
				post : true,
				reason : $scope.reason,
				what : $scope.what,
				problem : $scope.problem,
				solution : $scope.solution,
				found : $scope.found
			}, function(response) {
				$scope.x = response.data;
				}
			);          
            
        }
        
    });

</script>