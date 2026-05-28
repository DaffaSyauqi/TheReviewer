import { h } from 'vue';
import type { ColumnDef } from '@tanstack/vue-table';
import { ArrowUpDown, MoreHorizontal } from 'lucide-vue-next';
import { router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';

export interface Place {
    id: number;
    name: string;
    description: string;
    address: string;
    city: string;
    status: 'pending' | 'approved' | 'rejected';
    created_at: string;
}

export const columns: ColumnDef<Place>[] = [
    {
        accessorKey: 'name',
        header: ({ column }) =>
            h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => [h(ArrowUpDown, { class: 'mr-2 h-4 w-4' }), 'Name']
            ),
        cell: ({ row }) => h('div', { class: 'font-medium' }, row.getValue('name')),
    },
    {
        accessorKey: 'description',
        header: 'Description',
        cell: ({ row }) => {
            const description = row.getValue('description') as string;
            return h('div', { class: 'max-w-xs truncate text-sm' }, description);
        },
    },
    {
        accessorKey: 'address',
        header: 'Address',
        cell: ({ row }) => h('div', { class: 'max-w-xs truncate text-sm' }, row.getValue('address')),
    },
    {
        accessorKey: 'city',
        header: 'City',
    },
    {
        accessorKey: 'status',
        header: 'Status',
        cell: ({ row }) => {
            const status = row.getValue('status') as string;
            const statusClass =
                status === 'pending'
                    ? 'bg-yellow-100 text-yellow-800'
                    : status === 'approved'
                      ? 'bg-green-100 text-green-800'
                      : 'bg-red-100 text-red-800';

            return h(
                'div',
                { class: `px-2 py-1 rounded text-sm font-medium inline-block ${statusClass}` },
                status.charAt(0).toUpperCase() + status.slice(1)
            );
        },
    },
    {
        id: 'actions',
        enableHiding: false,
        cell: ({ row }) =>
            h(
                DropdownMenu,
                {},
                {
                    default: () => [
                        h(
                            DropdownMenuTrigger,
                            { asChild: true },
                            () =>
                                h(
                                    Button,
                                    { variant: 'ghost', class: 'h-8 w-8 p-0' },
                                    () => h(MoreHorizontal, { class: 'h-4 w-4' })
                                )
                        ),
                        h(
                            DropdownMenuContent,
                            { align: 'end' },
                            () =>
                                h(
                                    DropdownMenuItem,
                                    {
                                        onClick: () => {
                                            if (confirm('Are you sure you want to delete this place?')) {
                                                router.delete(`/manage-places/${row.original.id}`);
                                            }
                                        },
                                    },
                                    () => 'Delete'
                                )
                        ),
                    ],
                }
            ),
    },
];
