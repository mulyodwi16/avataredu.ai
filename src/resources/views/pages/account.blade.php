@extends('layouts.user-dashboard')

@section('title', 'Account Settings')
@section('page-title', 'Account Settings')

@section('content')
    <div class="space-y-6">
        <div class="bg-white rounded-2xl shadow p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Account Settings</h2>

            {{-- Profile Section --}}
            <div class="space-y-6">
                <div class="flex items-start gap-6">
                    <div class="w-24 h-24 rounded-full bg-primary/20 overflow-hidden">
                        <img src="{{ auth()->user()->avatar ?? 'https://i.pravatar.cc/96?img=5' }}" alt="avatar"
                            class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-800">Profile Picture</h3>
                        <p class="text-sm text-gray-500 mb-4">Upload a new profile picture</p>
                        <input type="file" class="hidden" id="avatar" accept="image/*">
                        <label for="avatar"
                            class="inline-block px-4 py-2 rounded-lg bg-primary text-white hover:bg-primaryDark cursor-pointer">
                            Upload New Picture
                        </label>
                    </div>
                </div>

                <hr class="border-gray-200">

                {{-- Personal Information Form --}}
                <form class="space-y-4">
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <input type="text" value="{{ auth()->user()->name }}"
                                class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" value="{{ auth()->user()->email }}"
                                class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input type="tel" placeholder="Enter your phone number"
                                class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                            <input type="date"
                                class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
                        <textarea rows="3" placeholder="Tell us about yourself"
                            class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary/30 focus:border-primary"></textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2 rounded-lg bg-primary text-white hover:bg-primaryDark">
                            Save Changes
                        </button>
                    </div>
                </form>

                <hr class="border-gray-200">

                {{-- Change Password Section --}}
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Change Password</h3>
                    <form class="space-y-4">
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                                <input type="password"
                                    class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary/30 focus:border-primary">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                                <input type="password"
                                    class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary/30 focus:border-primary">
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="px-6 py-2 rounded-lg bg-primary text-white hover:bg-primaryDark">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>

                <hr class="border-gray-200">

                {{-- Notification Settings --}}
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Notification Settings</h3>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3">
                            <input type="checkbox" class="w-4 h-4 text-primary">
                            <span class="text-sm text-gray-700">Email notifications for new courses</span>
                        </label>
                        <label class="flex items-center gap-3">
                            <input type="checkbox" class="w-4 h-4 text-primary">
                            <span class="text-sm text-gray-700">Email notifications for course updates</span>
                        </label>
                        <label class="flex items-center gap-3">
                            <input type="checkbox" class="w-4 h-4 text-primary">
                            <span class="text-sm text-gray-700">Email notifications for special offers</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
@endsection