@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10 sm:px-6 sm:py-16 lg:px-8 lg:py-24">

    <div class="max-w-xl mb-8 animate-fade-in-up sm:mb-12">
        <p class="mb-4 inline-flex items-center gap-2 text-[11px] font-bold uppercase tracking-[0.22em] text-amber-400/70 sm:mb-5 sm:text-xs sm:tracking-[0.3em]">
            <span class="w-5 h-px bg-amber-400/40"></span>
            Get In Touch
        </p>
        <h1 class="font-display text-3xl font-black tracking-tight text-stone-50 sm:text-5xl">
            Say hello anytime.
        </h1>
        <p class="mt-4 text-stone-500">Have questions, feedback, or just want to connect? We'd love to hear from you.</p>
    </div>

    <div class="grid gap-8 md:grid-cols-2 animate-fade-in-up delay-100">

        {{-- Contact Info --}}
        <div class="space-y-4">
            <div class="rounded-2xl border border-stone-800 bg-stone-900 p-4 flex items-start gap-4 sm:p-5">
                <div class="w-10 h-10 rounded-xl bg-amber-400/10 border border-amber-400/20 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-envelope text-amber-400 text-sm"></i>
                </div>
                <div>
                    <p class="text-xs text-stone-500 uppercase tracking-widest font-semibold">Email</p>
                    <p class="mt-1 text-sm text-stone-200 font-medium">brewlangcoffee@gmail.com</p>
                </div>
            </div>
            <div class="rounded-2xl border border-stone-800 bg-stone-900 p-4 flex items-start gap-4 sm:p-5">
                <div class="w-10 h-10 rounded-xl bg-amber-400/10 border border-amber-400/20 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-phone text-amber-400 text-sm"></i>
                </div>
                <div>
                    <p class="text-xs text-stone-500 uppercase tracking-widest font-semibold">Phone</p>
                    <p class="mt-1 text-sm text-stone-200 font-medium">+62 877-8324-5701</p>
                </div>
            </div>
            <div class="rounded-2xl border border-stone-800 bg-stone-900 p-4 flex items-start gap-4 sm:p-5">
                <div class="w-10 h-10 rounded-xl bg-amber-400/10 border border-amber-400/20 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-location-dot text-amber-400 text-sm"></i>
                </div>
                <div>
                    <p class="text-xs text-stone-500 uppercase tracking-widest font-semibold">Address</p>
                    <p class="mt-1 text-sm text-stone-200 font-medium">Jl. Komp. Japos No.n 2/1, RT.001/RW.014, Paninggilan, Kec. Ciledug, Kota Tangerang, Banten 15153</p>
                </div>
            </div>
            <div class="rounded-2xl border border-stone-800 bg-stone-900 p-4 flex items-start gap-4 sm:p-5">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126915.1562662319!2d106.63063265730533!3d-6.2507328916577025!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69fb0079f00a6f%3A0xe14745eab24faa04!2sWarung%20Kopi%20Brewlang!5e0!3m2!1sen!2sid!4v1778496465143!5m2!1sen!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>

        {{-- Contact Form --}}
        <div class="rounded-3xl border border-stone-800 bg-stone-900 p-4 sm:p-6">
            <h2 class="font-display text-xl font-bold text-stone-100 mb-6">Send a message</h2>
            @if(session('success'))
                <div class="alert-success-dark mb-4 flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-emerald-400 shrink-0"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert-error-dark mb-4 flex items-start gap-3">
                    <i class="fa-solid fa-circle-exclamation text-red-400 mt-0.5 shrink-0"></i>
                    <div class="text-sm space-y-1">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <form action="{{ route('contact.send') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="mb-1.5 block text-xs font-semibold text-stone-500 uppercase tracking-wider">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="input-dark" placeholder="Your name" required>
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-semibold text-stone-500 uppercase tracking-wider">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="input-dark" placeholder="you@email.com" required>
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-semibold text-stone-500 uppercase tracking-wider">Message</label>
                    <textarea name="message" rows="4" class="input-dark resize-none" placeholder="What's on your mind?" required>{{ old('message') }}</textarea>
                </div>
                <button type="submit" class="btn-primary w-full min-h-12 rounded-2xl! glow-amber mt-2">
                    <i class="fa-solid fa-paper-plane text-sm"></i>
                    Send Message
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
