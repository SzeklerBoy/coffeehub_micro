import { router } from "@inertiajs/vue3";

/**
 * Reloads data for specific properties using cursor-based pagination.
 *
 * This function triggers a reload of the specified properties (`propNames`)
 * by sending a request to the server with the provided `cursor`. It is
 * typically used for implementing cursor-based pagination in an Inertia.js
 * application.
 *
 * @param {string[]} propNames - An array of property names to reload.
 * @param {string|null} cursor - The cursor value for pagination. If null or
 *                               undefined, the function does nothing.
 *
 * @example
 * // Reloads the "allOrders" property with the next cursor
 * loadCursor(['allOrders'], 'next_cursor_value');
 *
 * @returns {void}
 */
export function loadCursor(propNames, cursor) {
    if (!cursor) {
        return;
    }
    router.reload({
        only: propNames,
        data: {
            cursor: cursor,
        },
    });
}
