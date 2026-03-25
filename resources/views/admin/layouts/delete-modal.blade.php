{{-- Global Delete Confirmation Modal --}}
<div id="delete-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    {{-- Backdrop --}}
    <div id="delete-modal-backdrop" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity opacity-0 duration-300 ease-out"></div>

    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        {{-- Modal Panel --}}
        <div id="delete-modal-content" class="relative transform overflow-hidden rounded-xl bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95 duration-300 ease-out">
            <div class="p-6">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-500"></i>
                    </div>
                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                        <h3 class="text-lg font-bold leading-6 text-gray-900 dark:text-white" id="modal-title">Delete Confirmation</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 dark:text-gray-400" id="delete-modal-message">
                                Are you sure you want to delete this item? This action cannot be undone.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 sm:flex sm:flex-row-reverse gap-3">
                <form id="delete-modal-form" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:w-auto transition-colors">
                        Delete Permanently
                    </button>
                </form>
                <button type="button" id="delete-modal-cancel" class="mt-3 inline-flex w-full justify-center rounded-lg bg-white dark:bg-gray-800 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-gray-300 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 sm:mt-0 sm:w-auto transition-colors">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
