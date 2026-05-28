<script setup lang="ts">
import {
    FlexRender,
    getCoreRowModel,
    getSortedRowModel,
    getFilteredRowModel,
    getPaginationRowModel,
    useVueTable,
    type ColumnDef,
    type SortingState,
} from '@tanstack/vue-table';
import { ref, computed } from 'vue';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';

interface Props<TData, TValue> {
    columns: ColumnDef<TData, TValue>[];
    data: TData[];
}

const props = defineProps<Props<any, any>>();

const sorting = ref<SortingState>([]);
const columnFilters = ref([]);
const globalFilter = ref('');

const table = useVueTable({
    get data() {
        return props.data;
    },
    get columns() {
        return props.columns;
    },
    getCoreRowModel: getCoreRowModel(),
    getPaginationRowModel: getPaginationRowModel(),
    getSortedRowModel: getSortedRowModel(),
    getFilteredRowModel: getFilteredRowModel(),
    state: {
        get sorting() {
            return sorting.value;
        },
        get columnFilters() {
            return columnFilters.value;
        },
        get globalFilter() {
            return globalFilter.value;
        },
    },
    onSortingChange: (updater) => {
        sorting.value =
            typeof updater === 'function' ? updater(sorting.value) : updater;
    },
    onColumnFiltersChange: (updater) => {
        columnFilters.value =
            typeof updater === 'function'
                ? updater(columnFilters.value)
                : updater;
    },
    onGlobalFilterChange: (updater) => {
        globalFilter.value =
            typeof updater === 'function'
                ? updater(globalFilter.value)
                : updater;
    },
});

const rows = computed(() => table.getRowModel().rows);
const pageCount = computed(() => table.getPageCount());
const currentPage = computed(() => table.getState().pagination.pageIndex + 1);
</script>

<template>
    <div class="space-y-4">
        <Input
            v-model="globalFilter"
            type="text"
            placeholder="Search places..."
            class="max-w-sm"
        />

        <div class="w-full overflow-x-auto rounded-lg border">
            <Table class="w-full">
                <TableHeader>
                    <TableRow
                        v-for="headerGroup in table.getHeaderGroups()"
                        :key="headerGroup.id"
                    >
                        <TableHead
                            v-for="header in headerGroup.headers"
                            :key="header.id"
                            class="whitespace-nowrap"
                        >
                            <FlexRender
                                v-if="!header.isPlaceholder"
                                :render="header.column.columnDef.header"
                                :props="header.getContext()"
                            />
                        </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <template v-if="rows.length > 0">
                        <TableRow
                            v-for="row in rows"
                            :key="row.id"
                            :data-state="row.getIsSelected() && 'selected'"
                        >
                            <TableCell
                                v-for="cell in row.getVisibleCells()"
                                :key="cell.id"
                                class="whitespace-nowrap"
                            >
                                <FlexRender
                                    :render="cell.column.columnDef.cell"
                                    :props="cell.getContext()"
                                />
                            </TableCell>
                        </TableRow>
                    </template>
                    <template v-else>
                        <TableRow>
                            <TableCell
                                :colSpan="table.getAllColumns().length"
                                class="h-24 text-center"
                            >
                                No places found.
                            </TableCell>
                        </TableRow>
                    </template>
                </TableBody>
            </Table>
        </div>

        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-500">
                Page {{ currentPage }} of {{ pageCount }}
            </div>
            <div class="flex gap-2">
                <Button
                    variant="outline"
                    size="sm"
                    @click="table.previousPage()"
                    :disabled="!table.getCanPreviousPage()"
                >
                    Previous
                </Button>
                <Button
                    variant="outline"
                    size="sm"
                    @click="table.nextPage()"
                    :disabled="!table.getCanNextPage()"
                >
                    Next
                </Button>
            </div>
        </div>
    </div>
</template>
