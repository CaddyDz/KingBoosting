<style>
.booster-card {
	background: radial-gradient(ellipse at left top,rgba(213,84,250,.5) 0,rgba(49,41,117,.5) 60%);
	height: 100%;
}
</style>
<div class="py-6" style="width: 100%; height: 315px">
  <div class="flex bg-white shadow-lg rounded-lg overflow-hidden booster-card">
	@if ($booster)
    <div class="w-1/3 bg-cover" style="background-image: url('/img/favicon.png')"></div>
    <div class="w-2/3 p-4">
      <h1 class="text-80 font-bold text-2xl">{{ $booster->username }}</h1>
	  <br>
	  <a href="{{ $booster->id }}" class="no-underline text-80">View Profile</a>
      <div class="flex item-center justify-between mt-3">
		{{-- <button class="px-3 py-2 bg-gray-800 text-white text-xl font-bold uppercase rounded">
			Extra Payment
		</button>
		<button class="px-3 py-2 bg-gray-800 text-white text-xl font-bold uppercase rounded">
			Change Email
		</button>
		<button class="px-3 py-2 bg-gray-800 text-white text-xl font-bold uppercase rounded">
			Spectate
		</button> --}}
      </div>
    </div>
	@else
	<div class="w-1/3 bg-cover" style="background-image: url('/img/favicon.png')"></div>
	<div class="w-2/3 p-4">
      <h1 class="text-80 font-bold text-2xl">@lang('Awaiting booster')</h1>
    </div>
	@endif
  </div>
</div>
