@php use App\Models\Employee;use App\Models\User; @endphp
@foreach( $users as $key => $user )
    <tr>
        @php
            $employee = $user->employee;
        @endphp
        <td>{{ $key + 1 ?? 'N/A' }}</td>
        <td>{{ $employee->employee_code ?? 'N/A' }}</td>
        <td>{{ $employee->full_name ?? 'N/A'  }}</td>
        <td>{{ $user->email ?? 'N/A'  }}</td>
        <td>{{ $user->role ? User::ROLES[$user->role] : 'N/A'  }}</td>
        <td>{{ $employee->dob ?? 'N/A'  }}</td>
        <td>{{ $employee->department->name ?? 'N/A'  }}</td>
        <td>{{ $employee->position->name ?? 'N/A'  }}</td>
        <td class="text-center">
            <a href="{{ route('general_catalog.showUpdateEmployee', $user->id) }}"
               class="btn btn-sm btn-secondary">
                <i class="fas fa-edit"></i>
            </a>
            <button
                class="btn btn-sm btn-info btn-view-employee"
                data-bs-toggle="modal"
                data-bs-target="#employeeDetailModal"
                data-avatar="{{ $employee->avatar ?? '/assets/img/no_avatar.jpg' }}"
                data-name="{{ $employee->full_name ?? '' }}"
                data-email="{{ $user->email ?? '' }}"
                data-phone="{{ $employee->phone ?? ''}}"
                data-position="{{ $employee->position->name ?? 'N/A' }}"
                data-department="{{ $employee->department->name ?? 'N/A' }}"
                data-code="{{ $employee->employee_code?? '' }}"
                data-gender="{{ $employee->gender ? Employee::GENDERS[$employee->gender] : '' }}"
                data-dob="{{ $employee->dob ?? '' }}"
                data-identity="{{ $employee->identity_number ?? '' }}"
                data-identity-date="{{ $employee->identity_issued_date ?? '' }}"
                data-identity-place="{{ $employee->identity_issued_place ?? '' }}"
                data-address="{{ $employee->address ?? '' }}"
                data-start-date="{{ $employee->start_date ?? '' }}"
                data-contract-type="{{ $employee->contract_type ?? '' }}"
                data-status="{{ $employee->employment_status ? Employee::STATUS_LIST[$employee->employment_status] : '' }}"
                data-base-salary="{{ $employee->base_salary ?? '' }}"
                data-factor="{{ $employee->salary_factor ?? '' }}"
                data-seniority="{{ $employee->seniority ?? '' }}"
                data-tax-code="{{ $employee->tax_code ?? '' }}"
                data-bank-account="{{ $employee->bank_account ?? '' }}"
                data-bank-name="{{ $employee->bank_name ?? '' }}"
                data-education="{{ $employee->education_level ?? '' }}"
                data-specialization="{{ $employee->specialization ?? '' }}"
                data-number_of_dependent="{{ $employee->number_of_dependent ?? '' }}"
            >
                <i class="fas fa-eye"></i>
            </button>
            <button class="btn btn-sm btn-danger"
            >
                <i class="fas fa-trash"></i>
            </button>
        </td>
    </tr>
@endforeach
