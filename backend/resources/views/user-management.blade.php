<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MedGemma Healthcare Platform') }} • User Management</title>
    @include('partials.global-styles')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            min-height: 100vh;
            line-height: 1.6;
        }
        .users-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding: 0.5rem 0;
        }
        .users-header h2 {
            font-size: 2.2rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            color: #fff;
            margin-bottom: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .users-actions button {
            font-size: 1rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(102,126,234,0.08);
            margin-right: 0.5rem;
            transition: box-shadow 0.2s;
        }
        .users-actions button:last-child { margin-right: 0; }
        .users-actions button:hover {
            box-shadow: 0 4px 16px rgba(118,75,162,0.15);
        }
        .users-list-container {
            background: rgba(255,255,255,0.04);
            border-radius: 18px;
            box-shadow: 0 4px 32px rgba(102,126,234,0.08);
            padding: 2rem 1rem 1rem 1rem;
            margin-bottom: 2rem;
        }
        .users-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 1rem;
        }
        .users-table th {
            background: rgba(102,126,234,0.12);
            color: #fff;
            font-weight: 600;
            padding: 1rem 0.75rem;
            border: none;
            font-size: 1.05rem;
            text-align: left;
        }
        .users-table td {
            background: rgba(255,255,255,0.08);
            color: #fff;
            font-size: 1rem;
            padding: 1.1rem 0.75rem;
            border-radius: 12px;
            vertical-align: middle;
            box-shadow: 0 2px 8px rgba(102,126,234,0.04);
            word-break: break-word;
            border: none;
        }
        .users-table tr {
            transition: box-shadow 0.2s, background 0.2s;
        }
        .users-table tr:not(.table-header):hover td {
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            box-shadow: 0 4px 16px rgba(118,75,162,0.15);
        }
        .users-table tr:not(.table-header) {
            border-bottom: 2px solid rgba(102,126,234,0.08);
        }
        .users-table td.actions {
            text-align: right;
            min-width: 140px;
        }
        .users-table td.name {
            font-weight: 600;
            letter-spacing: 0.01em;
            font-size: 1.08rem;
            padding-left: 1.2rem;
        }
        .users-table td {
            border-right: 1px solid rgba(102,126,234,0.04);
        }
        @media (max-width: 900px) {
            .users-list-container { padding: 1rem 0.2rem; }
            .users-table th, .users-table td { font-size: 0.95rem; padding: 0.7rem 0.4rem; }
        }
        @media (max-width: 600px) {
            .users-header { flex-direction: column; align-items: flex-start; }
            .users-actions { margin-top: 1rem; }
            .users-table th, .users-table td { font-size: 0.9rem; padding: 0.5rem 0.2rem; }
        }
        
        .app-header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 700;
            font-size: 1.2rem;
        }
        
        .mark {
            width: 32px;
            height: 32px;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
            border-radius: 8px;
        }
        
        .nav {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn {
            padding: 0.5rem 1rem;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }
        
        .btn.ghost {
            background: transparent;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .btn.ghost:hover, .btn.ghost.active {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }
        
        .btn.primary {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .btn.primary:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-1px);
        }
        
        .btn.outline, .btn.secondary {
            background: transparent;
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .btn.outline:hover, .btn.secondary:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .btn.small {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }
        
        .btn.danger {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        
        .btn.danger:hover {
            background: rgba(239, 68, 68, 0.3);
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        /* Mobile responsiveness improvements */
        @media (max-width: 1024px) {
            .container {
                padding: 0 0.5rem;
            }
            
            .grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .users-list {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }
        
        @media (max-width: 768px) {
            .row {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch !important;
            }
            
            .row h2 {
                margin-bottom: 1rem;
            }
            
            .row > div:last-child {
                margin-left: 0 !important;
                display: flex;
                gap: 0.5rem;
                flex-wrap: wrap;
            }
            
            .btn {
                flex: 1;
                min-width: 120px;
                text-align: center;
                justify-content: center;
            }
            
            .modal-content {
                width: 95%;
                max-width: none;
                margin: 1rem auto;
                max-height: 90vh;
                overflow-y: auto;
            }
            
            .form-row {
                flex-direction: column;
            }
            
            .form-group {
                margin-bottom: 1rem;
            }
            
            .form-actions {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .form-actions .btn {
                width: 100%;
            }
            
            .user-card {
                padding: 1rem;
            }
            
            .user-info h3 {
                font-size: 1rem;
            }
            
            .user-actions {
                gap: 0.5rem;
            }
            
            .user-actions .btn {
                padding: 0.4rem 0.8rem;
                font-size: 0.8rem;
                min-width: auto;
                flex: 1;
            }
        }
        
        @media (max-width: 480px) {
            .container {
                padding: 0 0.25rem;
            }
            
            .page-header h1 {
                font-size: 1.5rem;
            }
            
            .page-header .muted {
                font-size: 0.9rem;
            }
            
            .card {
                padding: 1rem;
            }
            
            .user-card {
                padding: 0.75rem;
            }
            
            .user-actions {
                flex-direction: column;
                gap: 0.25rem;
            }
            
            .user-actions .btn {
                font-size: 0.75rem;
                padding: 0.35rem 0.6rem;
            }
            
            .modal-header h3 {
                font-size: 1.1rem;
            }
            
            .btn-close {
                width: 30px;
                height: 30px;
                font-size: 1.2rem;
            }
        }        .page-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .page-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #fff 0%, rgba(255,255,255,0.8) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .page-header p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
        }
        
        .muted {
            color: rgba(255, 255, 255, 0.6);
        }
        
        .grid {
            display: grid;
            gap: 2rem;
            grid-template-columns: 1fr;
            margin-bottom: 2rem;
        }
        
        .card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 2rem;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .card h2 {
            margin-bottom: 1.5rem;
            color: #fff;
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        .row {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .input {
            padding: 0.75rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            font-size: 0.9rem;
        }
        
        .input:focus {
            outline: none;
            border-color: #4ecdc4;
            box-shadow: 0 0 0 3px rgba(78, 205, 196, 0.1);
        }
        
        .input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
        
        .input option {
            background: #2d3748;
            color: #fff;
        }
        
        .users-container {
            max-height: 700px;
            overflow-y: auto;
            overflow-x: hidden;
            margin-top: 1rem;
            padding: 1rem;
        }
        
        .user-card {
            display: grid;
            grid-template-columns: 80px 1fr auto;
            align-items: center;
            gap: 20px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .user-card:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        }
        
        .user-avatar {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .avatar-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: bold;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        
        .user-info {
            display: flex;
            flex-direction: column;
            gap: 6px;
            min-width: 0;
        }
        
        .user-info h3 {
            margin: 0;
            color: #ffffff;
            font-size: 18px;
            font-weight: 600;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .user-email {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0;
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .user-roles {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0;
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
            font-weight: 500;
        }
        
        .user-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.6);
            margin-top: 4px;
        }
        
        .user-meta span {
            background: rgba(255, 255, 255, 0.1);
            padding: 3px 8px;
            border-radius: 6px;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .user-meta span span, 
        .user-email span, 
        .user-roles span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 16px;
        }
        
        .doctor-share {
            background: rgba(34, 197, 94, 0.2);
            color: #86efac;
            padding: 6px 10px;
            border-radius: 6px;
            font-weight: 600;
            margin-top: 4px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 12px;
        }
        
        .doctor-share:hover {
            background: rgba(34, 197, 94, 0.3);
            transform: scale(1.02);
        }
        
        .doctor-share.not-set {
            background: rgba(239, 68, 68, 0.2);
            color: #fca5a5;
            animation: pulse 2s infinite;
        }
        
        .doctor-share.not-set:hover {
            background: rgba(239, 68, 68, 0.3);
        }
        
        .user-actions {
            display: flex;
            flex-direction: column;
            gap: 8px;
            align-items: stretch;
            min-width: 120px;
        }
        
        .btn.small {
            padding: 8px 12px;
            font-size: 12px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            font-weight: 500;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }
        
        .loading, .empty-state, .error-state {
            text-align: center;
            padding: 3rem 2rem;
            color: rgba(255, 255, 255, 0.6);
        }
        
        .error-state {
            color: #ef4444;
        }
        
        .user-item, .user-card {
            display: flex;
            align-items: stretch;
            gap: 1.5rem;
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            min-height: 120px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .user-item:hover, .user-card:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .user-avatar {
            flex-shrink: 0;
            display: flex;
            align-items: center;
        }
        
        .avatar-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.2rem;
        }
        
        .user-info {
            flex: 1;
            min-width: 0;
            overflow: hidden;
        }
        
        .user-info h3, .user-name {
            font-weight: 600;
            color: #fff;
            margin-bottom: 0.25rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .user-email {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .user-roles {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.8rem;
            margin-bottom: 0.25rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .user-meta {
            display: flex;
            gap: 1rem;
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.6);
            flex-wrap: wrap;
        }
        
        .user-actions {
            display: flex;
            gap: 0.5rem;
            flex-shrink: 0;
        }
        
        .tag {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }
        
        .tag.admin {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        
        .tag.doctor {
            background: rgba(34, 197, 94, 0.2);
            color: #22c55e;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }
        
        .tag.nurse {
            background: rgba(59, 130, 246, 0.2);
            color: #3b82f6;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }
        
        .tag.patient {
            background: rgba(245, 158, 11, 0.2);
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }
        
        .tag.staff {
            background: rgba(168, 85, 247, 0.2);
            color: #a855f7;
            border: 1px solid rgba(168, 85, 247, 0.3);
        }
        
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .modal-content {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0.08) 100%);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 16px;
            padding: 0;
            max-width: 550px;
            width: 90%;
            max-height: 90vh;
            overflow: hidden;
            color: #fff;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            animation: slideIn 0.3s ease;
        }
        
        @keyframes slideIn {
            from { 
                opacity: 0;
                transform: translateY(-20px) scale(0.95);
            }
            to { 
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 24px 24px 0 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 0;
        }
        
        .modal-header h3 {
            color: #fff;
            font-size: 20px;
            font-weight: 600;
            margin: 0;
            background: linear-gradient(135deg, #fff 0%, rgba(255,255,255,0.8) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .btn-close {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: rgba(255, 255, 255, 0.8);
            font-size: 18px;
            cursor: pointer;
            padding: 8px;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        
        .btn-close:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            transform: scale(1.05);
        }
        
        .modal-body {
            padding: 24px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            font-size: 14px;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
            background: rgba(255, 255, 255, 0.15);
        }
        
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
        
        .form-control option {
            background: #2d3748;
            color: #fff;
            padding: 10px;
        }
        
        .form-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 32px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .form-actions .btn {
            padding: 12px 24px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            min-width: 120px;
        }
        
        .form-actions .btn.primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        
        .form-actions .btn.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }
        
        .form-actions .btn.primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        .form-actions .btn.ghost {
            background: transparent;
            color: rgba(255, 255, 255, 0.8);
            border: 2px solid rgba(255, 255, 255, 0.2);
        }
        
        .form-actions .btn.ghost:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            border-color: rgba(255, 255, 255, 0.3);
        }
        
        .alert {
            padding: 16px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            backdrop-filter: blur(10px);
            border: 1px solid;
            font-weight: 500;
        }
        
        .alert-info {
            background: rgba(59, 130, 246, 0.15);
            border-color: rgba(59, 130, 246, 0.3);
            color: #93c5fd;
        }
        
        .alert-success {
            background: rgba(34, 197, 94, 0.15);
            border-color: rgba(34, 197, 94, 0.3);
            color: #86efac;
        }
        
        .alert-error {
            background: rgba(239, 68, 68, 0.15);
            border-color: rgba(239, 68, 68, 0.3);
            color: #fca5a5;
        }
        
        .loading, .error-state, .empty-state {
            text-align: center;
            padding: 60px 40px;
            color: rgba(255, 255, 255, 0.6);
            font-size: 16px;
        }
        
        .error-state {
            color: #fca5a5;
        }
        
        .loading::before {
            content: "⟳";
            display: inline-block;
            animation: spin 1s linear infinite;
            margin-right: 8px;
            font-size: 20px;
        }
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
.modal-content {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    padding: 2rem;
    max-width: 500px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
    color: #fff;
}

.form-row {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
}

.form-row .form-group {
    flex: 1;
    margin-bottom: 0;
}

@media (max-width: 768px) {
    .form-row {
        flex-direction: column;
        gap: 0;
    }
    
    .form-row .form-group {
        margin-bottom: 1rem;
    }
}        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .modal-header h3 {
            color: #fff;
            font-size: 1.25rem;
            font-weight: 600;
        }
        
        .btn-close {
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.6);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 4px;
            transition: all 0.2s;
        }
        
        .btn-close:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #fff;
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            font-size: 0.9rem;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #4ecdc4;
            box-shadow: 0 0 0 3px rgba(78, 205, 196, 0.1);
        }
        
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
        
        .form-control option {
            background: #2d3748;
            color: #fff;
        }
        
        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
        }
        
        
        @media (max-width: 768px) {
            .user-card {
                grid-template-columns: 60px 1fr;
                gap: 15px;
                padding: 15px;
            }
            
            .user-actions {
                grid-column: 1 / -1;
                flex-direction: row;
                justify-content: center;
                margin-top: 10px;
                gap: 8px;
            }
            
            .user-actions .btn {
                flex: 1;
                min-width: auto;
                font-size: 11px;
                padding: 6px 8px;
            }
            
            .avatar-circle {
                width: 50px;
                height: 50px;
                font-size: 16px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
            
            .modal-content {
                width: 95%;
                margin: 10px;
            }
            
            .modal-header, .modal-body {
                padding: 20px;
            }
        }
        
        @media (max-width: 480px) {
            .user-card {
                grid-template-columns: 1fr;
                text-align: center;
            }
            
            .user-info h3 {
                font-size: 16px;
            }
            
            .user-email, .user-roles {
                font-size: 13px;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .form-actions .btn {
                min-width: auto;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    @include('partials.header')
    
    <div class="container">
    <div class="page-header" style="margin-bottom: 20px;">
        <h1>User Management</h1>
        <p class="muted">Manage system users, roles, and permissions</p>
    </div>

    <!-- Alert container -->
    <div id="alert-container" style="margin-bottom: 20px;"></div>

    <div class="grid" style="margin-bottom: 20px;">
        <div class="card">
            <div class="row" style="align-items: center; margin-bottom: 16px;">
                <h2 style="margin: 0;">System Users</h2>
                <div style="margin-left: auto;">
                    <button class="btn primary" onclick="showCreateUserModal()">Add New User</button>
                    <button class="btn ghost" onclick="loadUsers()">Refresh</button>
                </div>
            </div>
            
            <div class="row" style="margin-bottom: 16px;">
                <input id="userSearch" type="search" placeholder="Search by name or email" class="input" style="flex:1; margin-right: 8px;">
                <select id="roleFilter" class="input" style="width: 150px;">
                    <option value="">All Roles</option>
                    <option value="Admin">Admin</option>
                    <option value="Doctor">Doctor</option>
                    <option value="Radiologist">Radiologist</option>
                    <option value="Pharmacist">Pharmacist</option>
                    <option value="Lab Technician">Lab Technician</option>
                    <option value="Pathologist">Pathologist</option>
                    <option value="Nurse">Nurse</option>
                    <option value="Receptionist">Receptionist</option>
                    <option value="Patient">Patient</option>
                </select>
            </div>

            <div id="users-list" class="users-container">
                <div class="loading">Loading users...</div>
            </div>
        </div>
    </div>
</div>

<!-- Create User Modal -->
<div id="createUserModal" class="modal" style="display:none;">
    <div class="modal-backdrop" onclick="closeCreateUserModal()"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3>Create New User</h3>
            <button class="btn-close" onclick="closeCreateUserModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="createUserForm">
                <div class="form-row">
                    <div class="form-group">
                        <label for="userName" class="form-label">Full Name</label>
                        <input type="text" id="userName" name="name" class="form-control" required placeholder="Enter full name">
                    </div>
                    <div class="form-group">
                        <label for="userEmail" class="form-label">Email Address</label>
                        <input type="email" id="userEmail" name="email" class="form-control" required placeholder="Enter email address">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="userPassword" class="form-label">Password</label>
                        <input type="password" id="userPassword" name="password" class="form-control" required placeholder="Enter password" minlength="6">
                    </div>
                    <div class="form-group">
                        <label for="userRole" class="form-label">Role</label>
                        <select id="userRole" name="role" class="form-control" required>
                            <option value="">Select Role</option>
                            <option value="Admin">Admin</option>
                            <option value="Doctor">Doctor</option>
                            <option value="Radiologist">Radiologist</option>
                            <option value="Pharmacist">Pharmacist</option>
                            <option value="Lab Technician">Lab Technician</option>
                            <option value="Pathologist">Pathologist</option>
                            <option value="Nurse">Nurse</option>
                            <option value="Receptionist">Receptionist</option>
                            <option value="Patient">Patient</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" id="sendWelcomeEmail" checked> Send welcome email to user
                    </label>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn primary">Create User</button>
                    <button type="button" class="btn ghost" onclick="closeCreateUserModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div id="editUserModal" class="modal" style="display:none;">
    <div class="modal-backdrop" onclick="closeEditUserModal()"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit User</h3>
            <button class="btn-close" onclick="closeEditUserModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="editUserForm">
                <input type="hidden" id="editUserId" name="id">
                <div class="form-row">
                    <div class="form-group">
                        <label for="editUserName" class="form-label">Full Name</label>
                        <input type="text" id="editUserName" name="name" class="form-control" required placeholder="Enter full name">
                    </div>
                    <div class="form-group">
                        <label for="editUserEmail" class="form-label">Email Address</label>
                        <input type="email" id="editUserEmail" name="email" class="form-control" required placeholder="Enter email address">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="editUserPassword" class="form-label">Password (leave blank to keep current)</label>
                        <input type="password" id="editUserPassword" name="password" class="form-control" placeholder="Enter new password" minlength="6">
                    </div>
                    <div class="form-group">
                        <label for="editUserRole" class="form-label">Role</label>
                        <select id="editUserRole" name="role" class="form-control" required>
                            <option value="">Select Role</option>
                            <option value="Admin">Admin</option>
                            <option value="Doctor">Doctor</option>
                            <option value="Radiologist">Radiologist</option>
                            <option value="Pharmacist">Pharmacist</option>
                            <option value="Lab Technician">Lab Technician</option>
                            <option value="Pathologist">Pathologist</option>
                            <option value="Nurse">Nurse</option>
                            <option value="Receptionist">Receptionist</option>
                            <option value="Patient">Patient</option>
                        </select>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn primary">Update User</button>
                    <button type="button" class="btn ghost" onclick="closeEditUserModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let allUsers = [];
let usersData = []; // Global variable to store users data for other functions

function htmlesc(str) { 
    return (str||'').toString().replace(/[&<>\"]/g, s=>({"&":"&amp;","<":"&lt;",">":"&gt;","\"":"&quot;"}[s])); 
}

function showAlert(message, type = 'info') {
    const alertContainer = document.getElementById('alert-container');
    const alertId = 'alert-' + Date.now();
    
    const alertHtml = `
        <div id="${alertId}" class="alert alert-${type}">
            ${htmlesc(message)}
            <button class="btn-close" onclick="document.getElementById('${alertId}').remove()">&times;</button>
        </div>
    `;
    
    alertContainer.innerHTML = alertHtml;
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        const alertEl = document.getElementById(alertId);
        if (alertEl) alertEl.remove();
    }, 5000);
}

async function loadUsers() {
    try {
        const response = await fetch('/api/users', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to load users');
        }
        
        const data = await response.json();
        allUsers = data;
        usersData = data; // Store for other functions to access
        renderUsers(allUsers);
    } catch (e) {
        document.getElementById('users-list').innerHTML = `
            <div class="error-state">
                <p>Failed to load users: ${htmlesc(e.message)}</p>
                <button class="btn primary" onclick="loadUsers()">Retry</button>
            </div>
        `;
    }
}

function filterUsers() {
    const search = document.getElementById('userSearch').value.toLowerCase();
    const roleFilter = document.getElementById('roleFilter').value;
    
    let filtered = allUsers.filter(user => {
        const matchesSearch = !search || 
            user.name.toLowerCase().includes(search) || 
            user.email.toLowerCase().includes(search);
        
        const userRoles = user.roles ? user.roles.map(r => r.name) : [];
        const matchesRole = !roleFilter || userRoles.includes(roleFilter);
        
        return matchesSearch && matchesRole;
    });
    
    renderUsers(filtered);
}

function renderUsers(users) {
    if (!users || users.length === 0) {
        document.getElementById('users-list').innerHTML = `
            <div class="empty-state">
                <p>No users found</p>
            </div>
        `;
        return;
    }
    
    const usersHtml = users.map(user => {
        // Safely extract user name and handle potential encryption issues
        let userName = user.name || 'Unknown User';
        let userEmail = user.email || 'No email';
        
        // If the name appears to be encrypted (base64), try to decode or use fallback
        if (userName.length > 50 && userName.includes('eyJ')) {
            userName = user.first_name && user.last_name 
                ? `${user.first_name} ${user.last_name}` 
                : `User ${user.id}`;
        }
        
        // Clean up user display names for known demo accounts
        if (userEmail === 'admin@medgemma.com') {
            userName = 'System Administrator';
        } else if (userEmail === 'doctor1@medgemma.com') {
            userName = 'Dr. John Smith';
        } else if (userEmail === 'doctor2@medgemma.com') {
            userName = 'Dr. Sarah Johnson';
        } else if (userName.startsWith('eyJ') || userName.length > 30) {
            // Handle encrypted names by using email prefix or User ID
            userName = userEmail.split('@')[0].replace(/[0-9]/g, '').toUpperCase() || `User ${user.id}`;
        }
        
        // Generate initials from the cleaned name
        const initials = userName.split(' ')
            .map(n => n.charAt(0))
            .join('')
            .substring(0, 2)
            .toUpperCase();
        
        // Extract roles safely
        const roles = user.roles && Array.isArray(user.roles) 
            ? user.roles.map(r => r.name || r).join(', ') 
            : (user.role || 'No roles');
        
        // Format creation date safely
        const createdDate = user.created_at 
            ? new Date(user.created_at).toLocaleDateString() 
            : 'Unknown';
        
        // Check if user is a doctor and add revenue share
        const isDoctor = roles.includes('Doctor');
        const hasRevenueShare = user.revenue_share !== null && user.revenue_share !== undefined;
        const doctorShareClass = hasRevenueShare ? 'doctor-share' : 'doctor-share not-set';
        const doctorShareHtml = isDoctor ? `
            <div class="${doctorShareClass}" onclick="editDoctorShare(${user.id}, '${htmlesc(userName)}')">
                💰 Revenue Share: ${hasRevenueShare ? user.revenue_share + '%' : 'Not Set'} <small>(Click to ${hasRevenueShare ? 'edit' : 'set'})</small>
            </div>
        ` : '';
        
        return `
            <div class="user-card" data-user-id="${user.id}">
                <div class="user-avatar">
                    <div class="avatar-circle">${htmlesc(initials)}</div>
                </div>
                <div class="user-info">
                    <h3>${htmlesc(userName)}</h3>
                    <p class="user-email"><span>📧</span> ${htmlesc(userEmail)}</p>
                    <p class="user-roles"><span>🎭</span> ${htmlesc(roles)}</p>
                    ${doctorShareHtml}
                    <div class="user-meta">
                        <span><span>🆔</span> ${user.id}</span>
                        <span><span>📅</span> ${createdDate}</span>
                        ${user.email_verified_at ? '<span style="color: #86efac;"><span>✅</span> Verified</span>' : '<span style="color: #fca5a5;"><span>⚠️</span> Unverified</span>'}
                    </div>
                </div>
                <div class="user-actions">
                    <button class="btn small primary" onclick="editUser(${user.id})">✏️ Edit</button>
                    ${isDoctor ? '<button class="btn small secondary" onclick="viewDoctorEarnings(' + user.id + ')">💰 Earnings</button>' : ''}
                    <button class="btn small danger" onclick="deleteUser(${user.id}, '${htmlesc(userName)}')">🗑️ Delete</button>
                </div>
            </div>
        `;
    }).join('');
    
    document.getElementById('users-list').innerHTML = usersHtml;
}

// Function to edit doctor revenue share
function editDoctorShare(userId, userName) {
    // Find the user to get current share
    const user = usersData.find(u => u.id == userId);
    const currentShare = user && user.revenue_share ? user.revenue_share : '';
    const promptText = currentShare 
        ? `Edit revenue share percentage for ${userName}:` 
        : `Set revenue share percentage for ${userName}:`;
    
    const newShare = prompt(promptText, currentShare);
    
    if (newShare !== null && !isNaN(newShare) && newShare >= 0 && newShare <= 100) {
        // Make API call to update the doctor's revenue share
        fetch(`/api/users/${userId}/revenue-share`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                revenue_share: parseFloat(newShare)
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                const action = currentShare ? 'updated' : 'set';
                showAlert(`Revenue share for ${userName} ${action} to ${newShare}%`, 'success');
                loadUsers(); // Refresh the user list
            } else {
                showAlert('Error updating revenue share', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error updating revenue share', 'error');
        });
    } else if (newShare !== null) {
        showAlert('Please enter a valid percentage between 0 and 100', 'error');
    }
}

// Function to view doctor earnings
function viewDoctorEarnings(userId) {
    fetch(`/api/users/${userId}/earnings`)
        .then(response => response.json())
        .then(data => {
            const earnings = data.total_earnings || 0;
            const procedures = data.total_procedures || 0;
            const revenueShare = data.revenue_share;
            const revenueShareSet = data.revenue_share_set;
            
            const revenueShareText = revenueShareSet 
                ? `Revenue Share: ${revenueShare}%` 
                : 'Revenue Share: Not Set';
            
            const earningsText = `
Doctor: ${data.doctor}
Total Earnings: $${parseFloat(earnings).toLocaleString()}
Total Procedures: ${procedures}
${revenueShareText}
            `;
            
            alert(earningsText);
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error fetching doctor earnings', 'error');
        });
}

// Function to edit user details
function editUser(userId) {
    showAlert('Edit user functionality - to be implemented', 'info');
    // Implementation for editing user details
}

// Function to delete user
function deleteUser(userId, userName) {
    if (confirm(`Are you sure you want to delete user: ${userName}?`)) {
        showAlert('Delete user functionality - to be implemented', 'info');
        // Implementation for deleting user
    }
}

function showCreateUserModal() {
    document.getElementById('createUserModal').style.display = 'block';
}

function closeCreateUserModal() {
    document.getElementById('createUserModal').style.display = 'none';
    document.getElementById('createUserForm').reset();
}

async function editUser(userId) {
    const user = allUsers.find(u => u.id === userId);
    if (!user) {
        showAlert('User not found', 'error');
        return;
    }
    
    // Populate edit form
    document.getElementById('editUserId').value = user.id;
    document.getElementById('editUserName').value = user.name;
    document.getElementById('editUserEmail').value = user.email;
    document.getElementById('editUserPassword').value = '';
    
    // Set role
    const userRole = user.roles && user.roles.length > 0 ? user.roles[0].name : '';
    document.getElementById('editUserRole').value = userRole;
    
    document.getElementById('editUserModal').style.display = 'block';
}

function closeEditUserModal() {
    document.getElementById('editUserModal').style.display = 'none';
    document.getElementById('editUserForm').reset();
}

async function deleteUser(userId, userName) {
    if (!confirm(`Are you sure you want to delete user "${userName}"? This action cannot be undone.`)) {
        return;
    }
    
    try {
        const response = await fetch(`/api/users/${userId}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf
            }
        });
        
        const responseData = await response.json();
        
        if (!response.ok) {
            throw new Error(responseData.message || 'Failed to delete user');
        }
        
        showAlert(`User "${userName}" deleted successfully`, 'success');
        loadUsers();
    } catch (e) {
        console.error('Delete user error:', e);
        showAlert(`Failed to delete user: ${e.message}`, 'error');
    }
}

// Form submissions
document.getElementById('createUserForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    console.log('Form submission started'); // Debug log
    
    const formData = new FormData(this);
    const data = {
        name: formData.get('name'),
        email: formData.get('email'),
        password: formData.get('password'),
        role: formData.get('role')
    };
    
    console.log('Form data:', data); // Debug log
    
    // Validate form data before sending
    if (!data.name || !data.email || !data.password || !data.role) {
        showAlert('Please fill in all required fields', 'error');
        return;
    }
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Creating...';
    submitBtn.disabled = true;
    
    try {
        console.log('Making API request...'); // Debug log
        
        const response = await fetch('/api/users', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf
            },
            body: JSON.stringify(data)
        });
        
        console.log('Response status:', response.status); // Debug log
        
        const responseData = await response.json();
        console.log('Response data:', responseData); // Debug log
        
        if (!response.ok) {
            throw new Error(responseData.message || 'Failed to create user');
        }
        
        showAlert('User created successfully', 'success');
        closeCreateUserModal();
        loadUsers();
    } catch (e) {
        console.error('Create user error:', e);
        showAlert(`Failed to create user: ${e.message}`, 'error');
    } finally {
        // Reset button state
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    }
});

document.getElementById('editUserForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const userId = formData.get('id');
    const data = {
        name: formData.get('name'),
        email: formData.get('email'),
        role: formData.get('role')
    };
    
    // Only include password if it's provided
    const password = formData.get('password');
    if (password && password.trim()) {
        data.password = password;
    }
    
    // Validate form data
    if (!data.name || !data.email || !data.role) {
        showAlert('Please fill in all required fields', 'error');
        return;
    }
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Updating...';
    submitBtn.disabled = true;
    
    try {
        const response = await fetch(`/api/users/${userId}`, {
            method: 'PUT',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf
            },
            body: JSON.stringify(data)
        });
        
        const responseData = await response.json();
        
        if (!response.ok) {
            throw new Error(responseData.message || 'Failed to update user');
        }
        
        showAlert('User updated successfully', 'success');
        closeEditUserModal();
        loadUsers();
    } catch (e) {
        console.error('Update user error:', e);
        showAlert(`Failed to update user: ${e.message}`, 'error');
    } finally {
        // Reset button state
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    }
});

// Search and filter event listeners
document.getElementById('userSearch').addEventListener('input', filterUsers);
document.getElementById('roleFilter').addEventListener('change', filterUsers);

// Initialize
loadUsers();

// Close modals when clicking outside
window.onclick = function(event) {
    const createModal = document.getElementById('createUserModal');
    const editModal = document.getElementById('editUserModal');
    
    if (event.target === createModal) {
        closeCreateUserModal();
    }
    
    if (event.target === editModal) {
        closeEditUserModal();
    }
}
</script>

<style>

</style>
</body>
</html>
