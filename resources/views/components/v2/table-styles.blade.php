<style>
    /* Modern Table Styling */
    .modern-table-wrapper {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .modern-table {
        margin-bottom: 0;
        border: none;
    }

    .modern-table thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .modern-table thead th {
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        padding: 1rem 0.75rem;
        border: none;
        white-space: nowrap;
    }

    .modern-table thead th.sortable {
        cursor: pointer;
        transition: all 0.2s ease;
        user-select: none;
    }

    .modern-table thead th.sortable:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .modern-table thead th i {
        margin-left: 5px;
        font-size: 0.85rem;
    }

    .modern-table tbody tr {
        border-bottom: 1px solid #9e9d9d;
        transition: background-color 0.2s ease;
    }

    .modern-table tbody tr:hover {
        background-color: #f8f9ff;
    }

    .modern-table tbody tr:last-child {
        border-bottom: none;
    }

    .modern-table tbody td {
        padding: 1rem 0.75rem;
        vertical-align: middle;
        border: none;
        font-size: 0.9rem;
    }

    .modern-table tbody td:first-child {
        font-weight: 600;
        color: #667eea;
    }

    .modern-table .badge {
        font-weight: 500;
        padding: 0.4rem 0.8rem;
        font-size: 0.75rem;
    }

    .modern-table .dropdown-toggle {
        border: none;
        background: rgba(102, 126, 234, 0.08);
        color: #667eea;
        padding: 0.5rem;
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 50%;
        font-size: 1rem;
        position: relative;
        overflow: hidden;
    }

    .modern-table .dropdown-toggle::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        transform: translate(-50%, -50%);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: -1;
    }

    .modern-table .dropdown-toggle:hover::before {
        width: 100%;
        height: 100%;
    }

    .modern-table .dropdown-toggle:hover {
        color: white;
        transform: rotate(90deg) scale(1.1);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .modern-table .dropdown-toggle:focus {
        color: white;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
    }

    .modern-table .dropdown-toggle i {
        position: relative;
        z-index: 1;
    }

    .dropdown-menu {
        border-radius: 16px;
        border: none;
        background: #ffffff;
        box-shadow: 
            0 20px 40px rgba(0, 0, 0, 0.12),
            0 8px 16px rgba(0, 0, 0, 0.08),
            0 0 0 1px rgba(102, 126, 234, 0.15);
        padding: 0.5rem;
        min-width: 220px;
        margin-top: 0.5rem;
        animation: dropdown-slide-in 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        overflow: hidden;
        position: relative;
        z-index: 1000;
    }

    @keyframes dropdown-slide-in {
        from {
            opacity: 0;
            transform: translateY(-20px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .dropdown-item {
        padding: 0.75rem 1rem;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 0.875rem;
        color: #495057;
        font-weight: 500;
        border-radius: 10px;
        margin: 0.15rem 0;
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
    }

    .dropdown-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 0;
        background: linear-gradient(90deg, rgba(102, 126, 234, 0.1) 0%, transparent 100%);
        transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 0;
    }

    .dropdown-item:hover::before {
        width: 100%;
    }

    .dropdown-item:hover {
        background: rgba(102, 126, 234, 0.08);
        color: #667eea;
        transform: translateX(4px);
    }

    .dropdown-item:active {
        transform: translateX(4px) scale(0.98);
    }

    .dropdown-item.text-danger::before {
        background: linear-gradient(90deg, rgba(220, 53, 69, 0.1) 0%, transparent 100%);
    }

    .dropdown-item.text-danger:hover {
        background: rgba(220, 53, 69, 0.08);
        color: #dc3545;
    }

    .dropdown-item.text-muted {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .dropdown-item.text-muted:hover {
        background: transparent;
        transform: none;
    }

    .dropdown-item.text-muted::before {
        display: none;
    }

    .dropdown-item i {
        width: 20px;
        height: 20px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.75rem;
        font-size: 1rem;
        transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        z-index: 1;
    }

    .dropdown-item:hover i {
        transform: scale(1.15);
    }

    .dropdown-item.text-danger:hover i {
        animation: shake 0.4s cubic-bezier(0.36, 0.07, 0.19, 0.97);
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0) scale(1.15); }
        25% { transform: translateX(-3px) scale(1.15); }
        75% { transform: translateX(3px) scale(1.15); }
    }

    .dropdown-divider {
        margin: 0.5rem 0.75rem;
        opacity: 0.1;
        border-top: 1px solid #667eea;
        position: relative;
    }

    .dropdown-divider::after {
        content: '';
        position: absolute;
        left: 0;
        top: -1px;
        width: 30%;
        height: 2px;
        background: linear-gradient(90deg, #667eea, transparent);
        opacity: 0.3;
    }

    /* Status Toggle Styling */
    .status-toggle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .status-toggle-checkbox {
        display: none;
    }

    .status-toggle-label {
        width: 44px;
        height: 24px;
        background: #ccc;
        border-radius: 12px;
        position: relative;
        cursor: pointer;
        transition: background 0.2s ease;
        margin: 0;
    }

    .status-toggle-label::after {
        content: '';
        position: absolute;
        width: 18px;
        height: 18px;
        background: white;
        border-radius: 50%;
        top: 3px;
        left: 3px;
        transition: transform 0.2s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
    }

    .status-toggle-checkbox:checked + .status-toggle-label {
        background: #198754;
    }

    .status-toggle-checkbox:checked + .status-toggle-label::after {
        transform: translateX(20px);
    }

    .status-toggle-label:hover {
        opacity: 0.9;
    }

    /* Responsive improvements */
    @media (max-width: 768px) {
        .modern-table thead th {
            font-size: 0.7rem;
            padding: 0.75rem 0.5rem;
        }
        
        .modern-table tbody td {
            padding: 0.75rem 0.5rem;
            font-size: 0.85rem;
        }
    }
</style>
