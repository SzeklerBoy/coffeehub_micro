<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import { useI18n } from "vue-i18n";
import { usePage } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import SeatModal from "@/Components/Desks/SeatModal.vue";
import RoomModal from "@/Components/Desks/RoomModal.vue";
import ClearModal from "@/Components/Desks/ClearModal.vue";
import ContextMenu from "@/Components/Desks/ContextMenu.vue";
import SidebarControls from "@/Components/Desks/SidebarControls.vue";
import CreateHeader from "@/Components/Desks/CreateHeader.vue";
import AdminButton from "@/Components/AdminButton.vue";

// i18n & props
const { t } = useI18n();
const page = usePage();
defineProps({ rooms: Array });

// === State ===
const desks = ref({});
const seats = ref([]);
const dragging = ref(false);
const isDeleted = ref(false);
const showMenu = ref(false);
const selectedId = ref(null);
const menuPosition = ref({ x: 0, y: 0 });
const showSuccessMessage = ref(false);
const isClearModalVisible = ref(false);
const isRoomUpdate = ref(false);
const isShowDeskModal = ref(false);
const selectedDeskIndex = ref(null);

const isShowRoomModal = ref(false);
const roomSize = ref({ width: 0, height: 0 });
const roomScale = ref(1);

// === Constants ===
const deskRadius = 0.75;
const seatRadius = 0.2;
const stageSize = {
    width: window.innerWidth * 0.65,
    height: window.innerHeight * 0.65,
};

// === Computed ===
const scaledBasedRect = computed(() => {
    const width = roomSize.value.width * roomScale.value;
    const height = roomSize.value.height * roomScale.value;
    return {
        x: (stageSize.width - width) / 2,
        y: (stageSize.height - height) / 2,
        width,
        height,
        stroke: "black",
        fill: "white",
        strokeWidth: 2,
    };
});

const deleteRect = {
    x: stageSize.width / 2 - 100,
    y: stageSize.height - 30,
    width: 200,
    height: 50,
    fill: "red",
    opacity: 0.7,
    stroke: "black",
    strokeWidth: 2,
    borderRadius: 10,
};

const deleteText = computed(() => ({
    x: deleteRect.x + 70,
    y: deleteRect.y - 30,
    text: t("desksPage.delete"),
    fontSize: 25,
    fill: "black",
    width: deleteRect.width,
}));

// === Lifecycle Hooks ===
onMounted(() => {
    window.addEventListener("click", hideContextMenu);
    askForRoom();
    fetchDesks();
});

onUnmounted(() => {
    window.removeEventListener("click", hideContextMenu);
});

// === Desk Handling ===
let lastDeskId = 0;

const addDesk = () => {
    const id = ++lastDeskId;
    const { x, y, width, height } = scaledBasedRect.value;

    desks.value[id] = {
        id,
        x: x + width / 2,
        y: y + height / 2,
        radius: deskRadius * roomScale.value,
        stroke: "black",
        strokeWidth: 2,
        draggable: true,
        nrOfSeats: 4,
        isPlaced: false,
        updated: true,
    };
};

const startDrag = () => {
    dragging.value = true;
    isDeleted.value = false;
};

const dragBounds = (pos) => {
    const rect = scaledBasedRect.value;
    return {
        x: Math.max(rect.x, Math.min(pos.x, rect.x + rect.width)),
        y: Math.max(rect.y, Math.min(pos.y, rect.y + rect.height)),
    };
};

const handleDeskDragEnd = async (event, deskId) => {
    const node = event.target;
    const newX = node.x();
    const newY = node.y();
    const desk = desks.value[deskId];

    if (
        newX >= deleteRect.x &&
        newX <= deleteRect.x + deleteRect.width &&
        newY >= deleteRect.y &&
        newY <= deleteRect.y + deleteRect.height
    ) {
        try {
            // eslint-disable-next-line no-undef
            await axios.delete(route("api.desks.destroy", { id: deskId }), {
                headers: getHeaders(),
            });
        } catch (e) {
            console.error("Delete failed:", e);
        }

        delete desks.value[deskId];
        seats.value = seats.value.filter((seat) => seat.parentId !== deskId);
        isDeleted.value = true;
    } else {
        desks.value[deskId] = { ...desk, x: newX, y: newY, updated: true };

        if (desk.isPlaced) {
            seats.value = seats.value.filter(
                (seat) => seat.parentId !== desk.id,
            );
            generateSeats(desks.value[deskId]);
        } else {
            showModal(deskId);
        }
    }

    dragging.value = false;
};

// === Seat Handling ===
const generateSeats = (desk) => {
    const { x, y, radius, nrOfSeats, id } = desk;
    const angle = 360 / nrOfSeats;

    seats.value = seats.value.filter((seat) => seat.parentId !== id);

    for (let i = 0; i < nrOfSeats; i++) {
        const rad = (angle * i * Math.PI) / 180;
        seats.value.push({
            x: x + radius * 1.5 * Math.cos(rad),
            y: y + radius * 1.5 * Math.sin(rad),
            radius: seatRadius * roomScale.value,
            fill: "#6a450b",
            stroke: "black",
            strokeWidth: 1,
            parentId: id,
        });
    }
};

const updateSeats = (nrOfSeats) => {
    if (selectedDeskIndex.value !== null) {
        const desk = desks.value[selectedDeskIndex.value];
        desk.nrOfSeats = nrOfSeats;
        desk.updated = true;
    }
};

// === Modal Handling ===
const showModal = (deskId) => {
    selectedDeskIndex.value = deskId;
    isShowDeskModal.value = true;
};

const closeModal = () => {
    if (selectedDeskIndex.value !== null) {
        const desk = desks.value[selectedDeskIndex.value];
        desk.isPlaced = true;
        generateSeats(desk);
    }
    isShowDeskModal.value = false;
};

const showRoomModal = () => (isShowRoomModal.value = true);
const closeRoomModal = () => (isShowRoomModal.value = false);

const handleRoomCreated = ({ width, length: length }) => {
    roomSize.value = { width, height: length };
    const scaleX = stageSize.width / width;
    const scaleY = stageSize.height / length;
    roomScale.value = Math.min(scaleX, scaleY);
    closeRoomModal();
    fetchDesks();
};

// === Context Menu ===
const handleContextMenu = (e, id) => {
    e.evt.preventDefault();
    const stage = e.target.getStage();
    const container = stage.container().getBoundingClientRect();
    const pos = stage.getPointerPosition();
    if (!pos) return;

    menuPosition.value = {
        x: container.left + pos.x + 4,
        y: container.top + pos.y + 4,
    };

    showMenu.value = true;
    selectedId.value = id;
    e.cancelBubble = true;
};

const hideContextMenu = () => (showMenu.value = false);
const hasOnGoingOrdersError = ref(false);

const handleSeatEdit = () => {
    const desk = Object.values(desks.value).find(
        (d) => d.id === selectedId.value,
    );
    if (desk) {
        showModal(desk.id);
    }
};

// === API Handling ===
const fetchDesks = async () => {
    try {
        // eslint-disable-next-line no-undef
        const res = await axios.get(route("api.desks.index"));
        const standaloneDesks = res.data?.desksProp?.standaloneDesks ?? [];

        desks.value = {};

        standaloneDesks.forEach((desk) => {
            desks.value[desk.id] = {
                ...desk,
                x: scaledBasedRect.value.x + desk.x * roomScale.value,
                y: scaledBasedRect.value.y + desk.y * roomScale.value,
                radius: deskRadius * roomScale.value,
                stroke: "black",
                strokeWidth: 2,
                draggable: true,
                isPlaced: true,
                updated: false,
            };

            lastDeskId = Math.max(lastDeskId, desk.id);
            generateSeats(desks.value[desk.id]);
        });
    } catch (error) {
        console.error(error);
        if (error.response && error.response.status === 500) {
            hasOnGoingOrdersError.value = true;
        }
    }
};

const save = async () => {
    const scale = roomScale.value;
    const toSave = Object.values(desks.value)
        .filter((d) => d.updated)
        .map(({ id, x, y, nrOfSeats }) => ({
            id,
            x: parseFloat(((x - scaledBasedRect.value.x) / scale).toFixed(2)),
            y: parseFloat(((y - scaledBasedRect.value.y) / scale).toFixed(2)),
            nrOfSeats,
        }));

    if (!toSave.length) {
        alert("No updates to save.");
        return;
    }

    try {
        /* eslint-disable no-undef */
        await axios.post(
            route("api.desks.store"),
            { desks: toSave },
            { headers: getHeaders() },
        );
        /* eslint-enable no-undef */

        Object.values(desks.value).forEach((d) => (d.updated = false));
        showSuccessMessage.value = true;

        setTimeout(() => {
            showSuccessMessage.value = false;
        }, 2000);
    } catch (error) {
        console.error(error);
    }
};

const askForRoom = async () => {
    try {
        // eslint-disable-next-line no-undef
        const res = await axios.get(route("api.rooms.index"), {
            headers: getHeaders(),
        });
        if (!res.data.length) return showRoomModal();

        const room = res.data[0];
        roomSize.value = {
            width: parseFloat(room.width),
            height: parseFloat(room.length),
        };

        const scaleX = stageSize.width / roomSize.value.width;
        const scaleY = stageSize.height / roomSize.value.height;
        roomScale.value = Math.min(scaleX, scaleY);
    } catch (e) {
        console.error("Room fetch error:", e);
    }
};

const clearCanvas = () => {
    isClearModalVisible.value = true;
};

const confirmClearCanvas = async () => {
    try {
        // eslint-disable-next-line no-undef
        await axios.delete(route("api.desks.destroyAll"), {
            headers: getHeaders(),
        });
        desks.value = {};
        seats.value = [];
        isClearModalVisible.value = false; // Close modal after clear
    } catch (e) {
        console.error("Clear canvas failed:", e);
    }
};

// === Helpers ===
const getHeaders = () => ({
    "Content-Type": "application/json",
    Accept: "application/json",
    "X-CSRF-TOKEN": page.props.csrf_token,
});

const lastTap = ref(0);
const handleTouchDesk = (event, deskId) => {
    const currentTime = new Date().getTime();
    const tapLength = currentTime - lastTap.value;

    if (tapLength < 300 && tapLength > 0) {
        // Double tap detected
        showModal(deskId);
    }

    lastTap.value = currentTime;
};

const handleChangeRoomSize = () => {
    isRoomUpdate.value = true;
    isShowRoomModal.value = true;
};
</script>

<template>
    <AdminLayout>
        <div
            v-if="showSuccessMessage"
            class="fixed top-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow-lg z-50"
        >
            {{ t("desksPage.saveSuccess") }}
        </div>

        <CreateHeader :room="rooms[0]" @changeSize="handleChangeRoomSize" />

        <div class="flex">
            <v-stage :config="stageSize">
                <v-layer>
                    <!-- Base Rectangle -->
                    <v-rect :config="scaledBasedRect" />

                    <!-- Delete Rectangle (only visible when dragging) -->
                    <v-rect v-if="dragging" :config="deleteRect" />
                    <v-text v-if="dragging" :config="deleteText" />

                    <!-- Desks as Groups -->
                    <v-group
                        v-for="desk in Object.values(desks)"
                        :key="desk.id"
                        :config="{
                            x: desk.x,
                            y: desk.y,
                            draggable: true,
                            dragBoundFunc: dragBounds,
                        }"
                        @dragstart="startDrag"
                        @dragend="(event) => handleDeskDragEnd(event, desk.id)"
                        @contextmenu="
                            (event) => handleContextMenu(event, desk.id)
                        "
                        @touchstart="(event) => handleTouchDesk(event, desk.id)"
                    >
                        <!-- Desk (Main Circle) -->
                        <v-circle
                            :config="{
                                x: 0,
                                y: 0,
                                radius: desk.radius,
                                stroke: desk.stroke,
                                strokeWidth: desk.strokeWidth,
                            }"
                        />

                        <!-- Seats for this desk -->
                        <v-circle
                            v-for="(seat, sIndex) in seats.filter(
                                (seat) => seat.parentId === desk.id,
                            )"
                            :key="sIndex"
                            :config="{
                                x: seat.x - desk.x,
                                y: seat.y - desk.y,
                                radius: seat.radius,
                                fill: seat.fill,
                            }"
                        />
                    </v-group>
                </v-layer>
            </v-stage>

            <!-- Context Menu -->
            <ContextMenu
                :visible="showMenu"
                :position="menuPosition"
                :menuOptions="['editSeats']"
                @editSeats="handleSeatEdit"
            />
            <!-- TODO: Add placeOrder and viewOrder options -->

            <SidebarControls
                @add-desk="addDesk"
                @clear-canvas="clearCanvas"
                @save="save"
            />
        </div>

        <SeatModal
            :isOpen="isShowDeskModal"
            :desk="desks[selectedDeskIndex]"
            @close="closeModal"
            @updateSeats="updateSeats"
        />
        <RoomModal
            :isOpen="isShowRoomModal"
            @close="closeRoomModal"
            @roomCreated="handleRoomCreated"
        />
        <RoomModal
            :isOpen="isShowRoomModal"
            :isUpdate="isRoomUpdate"
            :existing-room="rooms[0]"
            @close="closeRoomModal"
            @roomCreated="handleRoomCreated"
        />
        <ClearModal
            :isOpen="isClearModalVisible"
            :existing-room="rooms[0]"
            @close="isClearModalVisible = false"
            @confirm="confirmClearCanvas"
        />
        <div
            v-if="hasOnGoingOrdersError"
            class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50"
        >
            <div class="bg-white p-6 rounded shadow-lg max-w-md text-gray-900">
                <h2 class="text-xl font-semibold mb-4">
                    {{ t("desksPage.ongoingOrders.title") }}
                </h2>
                <p class="mb-6">
                    {{ t("desksPage.ongoingOrders.message") }}
                </p>
                <AdminButton
                    class="px-4 py-2"
                    @click="hasOnGoingOrdersError = false"
                >
                    {{ t("util.cancel") }}
                </AdminButton>
            </div>
        </div>
    </AdminLayout>
</template>
