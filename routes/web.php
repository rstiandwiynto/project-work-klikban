<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WorkspaceController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ProgressController;

// Public Landing Page (Image 4)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang-aplikasi', [HomeController::class, 'about'])->name('about');
Route::get('/panduan', [HomeController::class, 'about'])->name('guide');

// Auth Routes (Image 5)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::get('/demo-login', [AuthController::class, 'demoLogin'])->name('demo.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', [AuthController::class, 'logout']); // fallback

// Google OAuth Routes
Route::get('/auth/google/redirect', [AuthController::class, 'googleRedirect'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'googleCallback'])->name('auth.google.callback');
Route::get('/auth/mobile-login', [AuthController::class, 'mobileLogin'])->name('auth.mobile-login');

// Direct Invitation Link (Shareable by Admin)
Route::get('/workspaces/join-link/{code}', [WorkspaceController::class, 'joinLink'])->name('workspaces.join-link');

// Core App Routes
Route::middleware([])->group(function () {
    // Kanban Board View (Image 1)
    Route::get('/board', [TaskController::class, 'board'])->name('board');

    // Workspaces
    Route::post('/workspaces', [WorkspaceController::class, 'store'])->name('workspaces.store');
    Route::get('/workspaces/switch/{id}', [WorkspaceController::class, 'switchWorkspace'])->name('workspaces.switch');
    Route::post('/workspaces/join', [WorkspaceController::class, 'join'])->name('workspaces.join');
    Route::post('/workspaces/add-member', [WorkspaceController::class, 'addMember'])->name('workspaces.add-member');
    Route::post('/workspaces/{id}/leave', [WorkspaceController::class, 'leave'])->name('workspaces.leave');
    Route::post('/workspaces/{id}/remove-member/{userId}', [WorkspaceController::class, 'removeMember'])->name('workspaces.remove-member');

    // Projects
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/switch/{id}', [ProjectController::class, 'switchProject'])->name('projects.switch');
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    Route::post('/projects/{id}/delete', [ProjectController::class, 'destroy'])->name('projects.destroy.post');

    // Tasks CRUD & Quick Status Updates
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');
    Route::post('/tasks/{id}/status', [TaskController::class, 'updateStatus'])->name('tasks.status');
    Route::post('/tasks/{id}/priority', [TaskController::class, 'updatePriority'])->name('tasks.priority');
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::post('/tasks/{id}/delete', [TaskController::class, 'destroy'])->name('tasks.destroy.post');

    // Activity Log View (Image 3)
    Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log');

    // Progress Overview View
    Route::get('/progress', [ProgressController::class, 'index'])->name('progress');
});

