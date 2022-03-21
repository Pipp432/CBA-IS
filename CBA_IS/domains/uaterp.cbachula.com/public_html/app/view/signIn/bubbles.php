<html>
<head>
<script>
// for the pop sounds
document.addEventListener("DOMContentLoaded",() => {
	let bubbleWrap = document.getElementById("bw"),
		soundFolder = "https://jonkantner.com/experiments/sounds/",
		soundName = "bubble_wrap",
		beforeExt = `${soundFolder}${soundName}`,
		popSound = new Howl({
			src: [
				`${beforeExt}.mp3`,
				`${beforeExt}.ogg`
			],
			autoplay: false,
			loop: false,
			volume: 1.0
		});
	bubbleWrap.addEventListener("change",() => {
		popSound.play();
	});
});
const elts = {
	text1: document.getElementById("text1"),
	text2: document.getElementById("text2")
};

// The strings to morph between. You can change these to anything you want!
const texts = [
	"สู้ๆ",
	"นะ",
	"ค่ะ",
	"ทู้ก",
	"โคน"
];

// Controls the speed of morphing.
const morphTime = 1;
const cooldownTime = 0.25;

let textIndex = texts.length - 1;
let time = new Date();
let morph = 0;
let cooldown = cooldownTime;

elts.text1.textContent = texts[textIndex % texts.length];
elts.text2.textContent = texts[(textIndex + 1) % texts.length];

function doMorph() {
	morph -= cooldown;
	cooldown = 0;
	
	let fraction = morph / morphTime;
	
	if (fraction > 1) {
		cooldown = cooldownTime;
		fraction = 1;
	}
	
	setMorph(fraction);
}

// A lot of the magic happens here, this is what applies the blur filter to the text.
function setMorph(fraction) {
	// fraction = Math.cos(fraction * Math.PI) / -2 + .5;
	
	elts.text2.style.filter = `blur(${Math.min(8 / fraction - 8, 100)}px)`;
	elts.text2.style.opacity = `${Math.pow(fraction, 0.4) * 100}%`;
	
	fraction = 1 - fraction;
	elts.text1.style.filter = `blur(${Math.min(8 / fraction - 8, 100)}px)`;
	elts.text1.style.opacity = `${Math.pow(fraction, 0.4) * 100}%`;
	
	elts.text1.textContent = texts[textIndex % texts.length];
	elts.text2.textContent = texts[(textIndex + 1) % texts.length];
}

function doCooldown() {
	morph = 0;
	
	elts.text2.style.filter = "";
	elts.text2.style.opacity = "100%";
	
	elts.text1.style.filter = "";
	elts.text1.style.opacity = "0%";
}

// Animation loop, which is called every frame.
function animate() {
	requestAnimationFrame(animate);
	
	let newTime = new Date();
	let shouldIncrementIndex = cooldown > 0;
	let dt = (newTime - time) / 1000;
	time = newTime;
	
	cooldown -= dt;
	
	if (cooldown <= 0) {
		if (shouldIncrementIndex) {
			textIndex++;
		}
		
		doMorph();
	} else {
		doCooldown();
	}
}

// Start the animation.
animate();
</script>
<style>
* {
	border: 0;
	box-sizing: border-box;
	margin: 0;
	padding: 0;
}
:root {
	font-size: calc(20px + (30 - 20) * (100vw - 320px) / (1280 - 320));
}
body, button, input {
	font: 1em/1.5 Hind, system-ui, -apple-system, sans-serif;
}
body, .bw, .bw__bubbles {
	display: flex;
}
body {
	background-color: blue;
	height: 100vh;
}
.bw, .bw__bubbles {
	justify-content: center;
}
.bw {
	flex-direction: column;
	align-items: center;
	margin: auto;
}
.bw__bubbles {
	background-color: hsl(223,10%,58%);
	border-radius: 0.25em;
	box-shadow:
		0.1em 0 0 hsl(223,10%,75%) inset,
		-0.1em 0 0 hsl(223,10%,75%) inset;
	flex-wrap: wrap;
	align-content: flex-start;
	margin-bottom: 1.5em;
	mix-blend-mode: hard-light;
	width: 14em;
}
.bw__bubble {
	position: relative;
	width: 2em;
	height: 1.8em;
}
.bw__bubble:nth-child(13n) {
	margin-right: 1em;
}
.bw__bubble:nth-child(13n + 8) {
	margin-left: 1em;
}
.bw__btn, .bw__input {
	cursor: pointer;
	-webkit-appearance: none;
	appearance: none;
}
.bw__btn {
	background-color: white;
	border-radius: 0.2em;
	color: black;
	padding: 0.5em 1em;
	transition: background-color 0.15s linear;
}
.bw__btn:focus, .bw__btn:hover {
	background-color: black;
	color: white;
}
.bw__btn:active {
	transform: translateY(0.1em);
}
.bw__cover, .bw__cover:before, .bw__input, .bw__label {
	position: absolute;
}
.bw__cover, .bw__cover:before, .bw__input {
	border-radius: 50%;
}
.bw__cover, .bw__input {
	background-color: hsl(0,0%,70%);
	top: 0;
	left: 0.1em;
	width: 1.8em;
	height: 1.8em;
	transition: box-shadow 0.15s ease-in-out;
}
.bw__cover {
	display: block;
}
.bw__cover:before {
	background:
		linear-gradient(-50deg,hsla(0,0%,80%,0) 68%,hsl(0,0%,100%),hsla(0,0%,80%,0) 72%) 100% 0 / 75% 40%,
		linear-gradient(-20deg,hsla(0,0%,80%,0) 67%,hsl(0,0%,100%),hsla(0,0%,80%,0) 73%) 100% 0 / 75% 100%,
		linear-gradient(-80deg,hsla(0,0%,80%,0) 90%,hsl(0,0%,100%),hsla(0,0%,80%,0) 96%) 100% 100% / 80% 50%,
		linear-gradient(10deg,hsla(0,0%,80%,0) 44%,hsl(0,0%,100%),hsla(0,0%,80%,0) 50%) 100% 0 / 50% 100%,
		linear-gradient(-30deg,hsla(0,0%,80%,0) 21%,hsl(0,0%,100%),hsla(0,0%,80%,0) 27%) 20% 0 / 40% 100%,
		linear-gradient(110deg,hsla(0,0%,80%,0) 47%,hsl(0,0%,100%),hsla(0,0%,80%,0) 53%) 0 100% / 100% 30%,
		linear-gradient(-5deg,hsla(0,0%,70%,0) 55%,hsl(0,0%,70%),hsla(0,0%,70%,0) 62%) 100% 0 / 35% 100%,
		linear-gradient(25deg,hsla(0,0%,70%,0) 32%,hsl(0,0%,70%),hsla(0,0%,70%,0) 39%) 100% 0 / 50% 100%,
		linear-gradient(20deg,hsla(0,0%,70%,0) 22%,hsl(0,0%,70%),hsla(0,0%,70%,0) 29%) 100% 0 / 40% 100%;
	background-repeat: no-repeat;
	content: "";
	clip-path: circle(0% at 50% 50%);
	transition: clip-path 0.1s ease-in-out;
	width: 100%;
	height: 100%;
}
.bw__input {
	box-shadow:
		0 0 0 0.05em hsl(0,0%,85%) inset,
		0 0 0 hsl(223,10%,85%) inset,
		0.2em 0 0.1em hsl(223,10%,55%) inset,
		0 0.2em 0.1em hsl(223,10%,55%) inset,
		0.25em 0.25em 0.1em hsl(0,0%,100%) inset,
		0.6em 0.6em 0.4em 0.5em hsl(223,10%,55%) inset,
		0 0 0.25em hsl(223,10%,65%);
}
.bw__cover, .bw__input:checked, .bw__input:checked + .bw__label {
	visibility: hidden;
}
.bw__cover, .bw__input:focus, .bw__input:hover {
	box-shadow:
		0 0 0 0.05em hsl(0,0%,70%) inset,
		0 0 0 hsl(223,10%,85%) inset,
		0.1em 0 0.1em hsl(223,10%,55%) inset,
		0 0.1em 0.1em hsl(223,10%,55%) inset,
		0.15em 0.15em 0.1em hsl(0,0%,100%) inset,
		0.7em 0.7em 0.4em 0.5em hsl(223,10%,55%) inset,
		0 0 0.25em hsl(223,10%,65%);
}
.bw__input:focus {
	outline: transparent;
}
.bw__input:checked ~ .bw__cover {
	box-shadow:
		0 0 0 0.05em hsl(0,0%,70%) inset,
		-0.2em -0.2em 0.2em hsl(223,10%,65%) inset,
		0 0 0 hsl(223,10%,55%) inset,
		0 0 0 hsl(223,10%,55%) inset,
		0.1em 0.1em 0.1em hsl(0,0%,90%) inset,
		0.7em 0.7em 0.4em 0.5em hsl(223,10%,60%) inset,
		0 0 0.25em hsl(223,10%,65%);
	visibility: visible;
}
.bw__input:checked ~ .bw__cover:before {
	clip-path: circle(50% at 50% 50%);
}
.bw__label {
	clip: rect(1px,1px,1px,1px);
	overflow: hidden;
	width: 1px;
	height: 1px;
}
@import url('https://fonts.googleapis.com/css?family=Raleway:900&display=swap');

#container {
	/* Center the text in the viewport. */
	position: absolute;
	margin: auto;
	width: 100vw;
	height: 80pt;
	top: 0;
	bottom: 0;
	
	/* This filter is a lot of the magic, try commenting it out to see how the morphing works! */
	filter: url(#threshold) blur(0.6px);
}

/* Your average text styling */
#text1, #text2 {
	position: absolute;
	width: 100%;
	display: inline-block;
	
	font-family: 'Raleway', sans-serif;
	font-size: 80pt;
	color: white;
	text-align: center;
	
	user-select: none;
}
</style>
</head>
<body>
<div class="row">
<div class="col-6">
<form class="bw" id="bw">
  <div class="bw__bubbles">
    <div class="bw__bubble">
      <input class="bw__input" id="b1" type="checkbox" name="b1" value="1"/>
      <label class="bw__label" for="b1">Bubble 1</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b2" type="checkbox" name="b2" value="2"/>
      <label class="bw__label" for="b2">Bubble 2</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b3" type="checkbox" name="b3" value="3"/>
      <label class="bw__label" for="b3">Bubble 3</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b4" type="checkbox" name="b4" value="4"/>
      <label class="bw__label" for="b4">Bubble 4</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b5" type="checkbox" name="b5" value="5"/>
      <label class="bw__label" for="b5">Bubble 5</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b6" type="checkbox" name="b6" value="6"/>
      <label class="bw__label" for="b6">Bubble 6</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b7" type="checkbox" name="b7" value="7"/>
      <label class="bw__label" for="b7">Bubble 7</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b8" type="checkbox" name="b8" value="8"/>
      <label class="bw__label" for="b8">Bubble 8</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b9" type="checkbox" name="b9" value="9"/>
      <label class="bw__label" for="b9">Bubble 9</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b10" type="checkbox" name="b10" value="10"/>
      <label class="bw__label" for="b10">Bubble 10</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b11" type="checkbox" name="b11" value="11"/>
      <label class="bw__label" for="b11">Bubble 11</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b12" type="checkbox" name="b12" value="12"/>
      <label class="bw__label" for="b12">Bubble 12</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b13" type="checkbox" name="b13" value="13"/>
      <label class="bw__label" for="b13">Bubble 13</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b14" type="checkbox" name="b14" value="14"/>
      <label class="bw__label" for="b14">Bubble 14</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b15" type="checkbox" name="b15" value="15"/>
      <label class="bw__label" for="b15">Bubble 15</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b16" type="checkbox" name="b16" value="16"/>
      <label class="bw__label" for="b16">Bubble 16</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b17" type="checkbox" name="b17" value="17"/>
      <label class="bw__label" for="b17">Bubble 17</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b18" type="checkbox" name="b18" value="18"/>
      <label class="bw__label" for="b18">Bubble 18</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b19" type="checkbox" name="b19" value="19"/>
      <label class="bw__label" for="b19">Bubble 19</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b20" type="checkbox" name="b20" value="20"/>
      <label class="bw__label" for="b20">Bubble 20</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b21" type="checkbox" name="b21" value="21"/>
      <label class="bw__label" for="b21">Bubble 21</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b22" type="checkbox" name="b22" value="22"/>
      <label class="bw__label" for="b22">Bubble 22</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b23" type="checkbox" name="b23" value="23"/>
      <label class="bw__label" for="b23">Bubble 23</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b24" type="checkbox" name="b24" value="24"/>
      <label class="bw__label" for="b24">Bubble 24</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b25" type="checkbox" name="b25" value="25"/>
      <label class="bw__label" for="b25">Bubble 25</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b26" type="checkbox" name="b26" value="26"/>
      <label class="bw__label" for="b26">Bubble 26</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b27" type="checkbox" name="b27" value="27"/>
      <label class="bw__label" for="b27">Bubble 27</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b28" type="checkbox" name="b28" value="28"/>
      <label class="bw__label" for="b28">Bubble 28</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b29" type="checkbox" name="b29" value="29"/>
      <label class="bw__label" for="b29">Bubble 29</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b30" type="checkbox" name="b30" value="30"/>
      <label class="bw__label" for="b30">Bubble 30</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b31" type="checkbox" name="b31" value="31"/>
      <label class="bw__label" for="b31">Bubble 31</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b32" type="checkbox" name="b32" value="32"/>
      <label class="bw__label" for="b32">Bubble 32</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b33" type="checkbox" name="b33" value="33"/>
      <label class="bw__label" for="b33">Bubble 33</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b34" type="checkbox" name="b34" value="34"/>
      <label class="bw__label" for="b34">Bubble 34</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b35" type="checkbox" name="b35" value="35"/>
      <label class="bw__label" for="b35">Bubble 35</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b36" type="checkbox" name="b36" value="36"/>
      <label class="bw__label" for="b36">Bubble 36</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b37" type="checkbox" name="b37" value="37"/>
      <label class="bw__label" for="b37">Bubble 37</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b38" type="checkbox" name="b38" value="38"/>
      <label class="bw__label" for="b38">Bubble 38</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b39" type="checkbox" name="b39" value="39"/>
      <label class="bw__label" for="b39">Bubble 39</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b40" type="checkbox" name="b40" value="40"/>
      <label class="bw__label" for="b40">Bubble 40</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b41" type="checkbox" name="b41" value="41"/>
      <label class="bw__label" for="b41">Bubble 41</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b42" type="checkbox" name="b42" value="42"/>
      <label class="bw__label" for="b42">Bubble 42</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b43" type="checkbox" name="b43" value="43"/>
      <label class="bw__label" for="b43">Bubble 43</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b44" type="checkbox" name="b44" value="44"/>
      <label class="bw__label" for="b44">Bubble 44</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b45" type="checkbox" name="b45" value="45"/>
      <label class="bw__label" for="b45">Bubble 45</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b46" type="checkbox" name="b46" value="46"/>
      <label class="bw__label" for="b46">Bubble 46</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b47" type="checkbox" name="b47" value="47"/>
      <label class="bw__label" for="b47">Bubble 47</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b48" type="checkbox" name="b48" value="48"/>
      <label class="bw__label" for="b48">Bubble 48</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b49" type="checkbox" name="b49" value="49"/>
      <label class="bw__label" for="b49">Bubble 49</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b50" type="checkbox" name="b50" value="50"/>
      <label class="bw__label" for="b50">Bubble 50</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b51" type="checkbox" name="b51" value="51"/>
      <label class="bw__label" for="b51">Bubble 51</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b52" type="checkbox" name="b52" value="52"/>
      <label class="bw__label" for="b52">Bubble 52</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b53" type="checkbox" name="b53" value="53"/>
      <label class="bw__label" for="b53">Bubble 53</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b54" type="checkbox" name="b54" value="54"/>
      <label class="bw__label" for="b54">Bubble 54</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b55" type="checkbox" name="b55" value="55"/>
      <label class="bw__label" for="b55">Bubble 55</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b56" type="checkbox" name="b56" value="56"/>
      <label class="bw__label" for="b56">Bubble 56</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b57" type="checkbox" name="b57" value="57"/>
      <label class="bw__label" for="b57">Bubble 57</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b58" type="checkbox" name="b58" value="58"/>
      <label class="bw__label" for="b58">Bubble 58</label><span class="bw__cover"></span>
    </div>
    <div class="bw__bubble">
      <input class="bw__input" id="b59" type="checkbox" name="b59" value="59"/>
      <label class="bw__label" for="b59">Bubble 59</label><span class="bw__cover"></span>
    </div>
  </div>
  <button class="bw__btn" type="reset">ไปเอาแผ่นใหม่มาค่ะ</button>
</form>
</div>
<div class="col-6">
<div id="container">
	<span id="text1"></span>
	<span id="text2"></span>
</div>
<svg id="filters">
	<defs>
		<filter id="threshold">
			<!-- Basically just a threshold effect - pixels with a high enough opacity are set to full opacity, and all other pixels are set to completely transparent. -->
			<feColorMatrix in="SourceGraphic"
					type="matrix"
					values="1 0 0 0 0
									0 1 0 0 0
									0 0 1 0 0
									0 0 0 255 -140" />
		</filter>
	</defs>
</svg>
</div>
</div>
</body>
</html>