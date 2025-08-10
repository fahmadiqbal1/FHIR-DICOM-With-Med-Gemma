<?php

// This file is part of the OpenEMR project.
//
// OpenEMR is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// OpenEMR is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with OpenEMR.  If not, see <https://www.gnu.org/licenses/>.

// SPDX-License-Identifier: GPL-3.0-only

declare(strict_types=1);

/*
 * OpenEMR is a comprehensive healthcare information management solution.
 * For more information, visit https://www.open-emr.org.
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson() || $request->is('api/*')) {
            $logs = AuditLog::with('user')
                ->orderByDesc('created_at')
                ->limit(200)
                ->get()
                ->map(function ($log) {
                    return [
                        'created_at' => $log->created_at->toDateTimeString(),
                        'user_name' => $log->user ? ($log->user->name ?? $log->user->email) : null,
                        'action' => $log->action,
                        'subject_type' => class_basename($log->subject_type),
                        'subject_id' => $log->subject_id,
                        'ip' => $log->ip,
                        'details' => $log->details,
                    ];
                });
            if ($request->query('export') === 'csv') {
                $csv = "Date,User,Action,Subject,IP,Details\n";
                foreach ($logs as $log) {
                    $csv .= '"'.implode('","', [
                        $log['created_at'],
                        $log['user_name'],
                        $log['action'],
                        $log['subject_type'].' #'.$log['subject_id'],
                        $log['ip'],
                        json_encode($log['details'])
                    ]).'"\n';
                }
                return Response::make($csv, 200, [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="audit-logs.csv"',
                ]);
            }
            return response()->json($logs);
        }
        return view('admin-audit-logs');
    }
}
