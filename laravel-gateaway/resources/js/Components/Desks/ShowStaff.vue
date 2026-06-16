<script setup>
import { computed, onMounted, onUnmounted, ref } from "vue";
import ContextMenu from "@/Components/Desks/ContextMenu.vue";
import { Inertia } from "@inertiajs/inertia";
import axios from "axios";
import { useI18n } from "vue-i18n";
import AdminButton from "@/Components/AdminButton.vue";
import DangerButton from "@/Components/DangerButton.vue";

const props = defineProps({
    rooms: Array,
});

const desks = ref({});
const seats = ref([]);
const roomSize = ref({ width: 0, height: 0 });
const roomScale = ref(1);
const { t } = useI18n();

const deskRadius = 0.75;
const seatRadius = 0.2;
const stageSize = {
    width: window.innerWidth * 0.75,
    height: window.innerHeight * 0.75,
};

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

const generateSeats = (desk) => {
    const { x, y, nrOfSeats, id } = desk;
    const angle = 360 / nrOfSeats;
    const radiusX = desk.isOval ? desk.width / 2 : desk.radius;
    const radiusY = desk.isOval ? desk.height / 2 : desk.radius;

    seats.value = seats.value.filter((seat) => seat.parentId !== id);

    for (let i = 0; i < nrOfSeats; i++) {
        const rad = (angle * i * Math.PI) / 180;
        seats.value.push({
            x: x + radiusX * 1.5 * Math.cos(rad),
            y: y + radiusY * 1.5 * Math.sin(rad),
            radius: seatRadius * roomScale.value,
            fill: "#6a450b",
            stroke: "black",
            strokeWidth: 1,
            parentId: id,
        });
    }
};

onMounted(async () => {
    window.removeEventListener("click", hideContextMenu);

    if (props.rooms.length > 0) {
        const room = props.rooms[0];
        roomSize.value = {
            width: parseFloat(room.width),
            height: parseFloat(room.length),
        };
        const scaleX = stageSize.width / roomSize.value.width;
        const scaleY = stageSize.height / roomSize.value.height;
        roomScale.value = Math.min(scaleX, scaleY);
    }

    // Fetch desks and groups
    await fetchDesks();
});

onUnmounted(() => {
    window.removeEventListener("click", hideContextMenu);
});

const menuPosition = ref({ x: 0, y: 0 });
const selectedId = ref(null);
const showMenu = ref(false);

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

const handlePlaceOrder = () => {
    hideContextMenu();
    const selectedDesk = desks.value[selectedId.value];

    if (!selectedDesk) return;

    if (selectedDesk.groupId) {
        // Send groupId instead of desk
        // eslint-disable-next-line no-undef
        Inertia.get(route("orders.create"), {
            group: selectedDesk.groupId,
        });
    } else {
        // eslint-disable-next-line no-undef
        Inertia.get(route("orders.create"), {
            desk: selectedId.value,
        });
    }
};

const handleViewOrder = () => {
    hideContextMenu();
    //TODO: Handle view order logic here
};

const generateCode = async () => {
    hideContextMenu();
    const response = await axios.get(
        // eslint-disable-next-line no-undef
        route("desks.code.create", selectedId.value),
    );
    desks.value[selectedId.value].code = response.data.code;
    await fetchDesks();
};

const clearCode = async () => {
    hideContextMenu();
    await axios.get(
        // eslint-disable-next-line no-undef
        route("desks.code.delete", selectedId.value),
    );
    desks.value[selectedId.value].code = null;
    await fetchDesks();
};

const fetchDesks = async () => {
    try {
        // eslint-disable-next-line no-undef
        const res = await axios.get(route("api.desks.index"));
        const { groups, standaloneDesks } = res.data?.desksProp ?? { groups: [], standaloneDesks: [] };

        // Use a separate function to reset desks and seats
        resetDesksAndSeats();

        // Handle standalone desks
        standaloneDesks.forEach((desk) => {
            desks.value[desk.id] = {
                ...desk,
                x: scaledBasedRect.value.x + desk.x * roomScale.value,
                y: scaledBasedRect.value.y + desk.y * roomScale.value,
                radius: deskRadius * roomScale.value,
                stroke: "black",
                fill: desk.is_occupied ? "#ff5050" : "#66ff66",
                strokeWidth: 2,
                draggable: true,
                isPlaced: true,
                updated: false,
            };
            generateSeats(desks.value[desk.id]);
        });

        // Handle group desks as merged
        groups.forEach((group) => {
            const groupDesks = group.desks;

            if (groupDesks.length < 2) return; // Skip if not merged

            const mergedId = `merged-${group.id}`;
            const mergedX =
                groupDesks.reduce((sum, d) => sum + d.x, 0) / groupDesks.length;
            const mergedY =
                groupDesks.reduce((sum, d) => sum + d.y, 0) / groupDesks.length;
            const mergedSeats = groupDesks.reduce(
                (sum, d) => sum + d.nrOfSeats,
                0,
            );

            desks.value[mergedId] = {
                id: mergedId,
                x: scaledBasedRect.value.x + mergedX * roomScale.value,
                y: scaledBasedRect.value.y + mergedY * roomScale.value,
                radius: deskRadius * roomScale.value,
                width: deskRadius * roomScale.value * 3,
                height: deskRadius * roomScale.value * 2.2,
                stroke: "black",
                fill: "#ff5050",
                strokeWidth: 2,
                draggable: true,
                isOval: true,
                nrOfSeats: mergedSeats,
                groupId: group.id,
            };

            generateSeats(desks.value[mergedId]);
        });
    } catch (error) {
        console.error(error);
    }
};

// New function to reset desks and seats
const resetDesksAndSeats = () => {
    desks.value = {};
    seats.value = [];
};

const handleDragEnd = (e, id) => {
    const { x, y } = e.target.position();
    desks.value[id].x = x;
    desks.value[id].y = y;
    desks.value[id].updated = true;

    generateSeats(desks.value[id]);

    for (const otherId in desks.value) {
        if (otherId !== id.toString()) {
            if (areCirclesColliding(desks.value[id], desks.value[otherId])) {
                collisionPair.value = [id, otherId];
                showCollisionModal.value = true;
                break;
            }
        }
    }
};

const showCollisionModal = ref(false);
const collisionPair = ref([]);

const areCirclesColliding = (desk1, desk2) => {
    const dx = desk1.x - desk2.x;
    const dy = desk1.y - desk2.y;
    const distance = Math.sqrt(dx * dx + dy * dy);
    return distance < desk1.radius + desk2.radius;
};

const mergeDesks = async () => {
    const [id1, id2] = collisionPair.value;
    const desk1 = desks.value[id1];
    const desk2 = desks.value[id2];

    // Calculate merged properties
    const mergedX = (desk1.x + desk2.x) / 2;
    const mergedY = (desk1.y + desk2.y) / 2;
    const mergedSeats = desk1.nrOfSeats + desk2.nrOfSeats;
    const mergedRadius = deskRadius * roomScale.value;
    const mergedId = `merged-${id1}-${id2}`;

    try {
        // eslint-disable-next-line no-undef
        const response = await axios.post(route("groups.store"), {
            desks: [parseInt(id1), parseInt(id2)],
        });

        const newGroupId = response.data?.id;
        if (!newGroupId) throw new Error("No group ID returned");

        // Add merged desk with new groupId
        desks.value[mergedId] = {
            id: mergedId,
            x: mergedX,
            y: mergedY,
            radius: mergedRadius,
            width: mergedRadius * 3,
            height: mergedRadius * 2.2,
            stroke: "black",
            fill: "#ff5050",
            strokeWidth: 2,
            draggable: true,
            nrOfSeats: mergedSeats,
            isOval: true,
            groupId: newGroupId,
        };

        selectedId.value = mergedId;

        generateSeats(desks.value[mergedId]);

        // Remove originals
        delete desks.value[id1];
        delete desks.value[id2];
        seats.value = seats.value.filter(
            (seat) => seat.parentId !== id1 && seat.parentId !== id2,
        );

        showCollisionModal.value = false;
    } catch (error) {
        console.error("Failed to store group:", error.response?.data || error);
    }
};

const isGroupDesk = computed(() => {
    const desk = desks.value[selectedId.value];
    return desk && desk.id?.toString().startsWith("merged-");
});

const selectedGroupId = computed(() => {
    const desk = desks.value[selectedId.value];
    return desk?.groupId ?? null;
});

const deleteGroup = async () => {
    hideContextMenu();
    const desk = desks.value[selectedId.value];
    const groupId = desk?.groupId;

    try {
        // eslint-disable-next-line no-undef
        await axios.delete(route("groups.destroy", groupId));

        // Remove the deleted group's desks and reset
        resetDesksAndSeats();

        // Re-fetch updated desks from backend to ensure UI is in sync
    } catch (error) {
        console.error("Failed to delete group:", error.response?.data || error);
    }
    await fetchDesks();
};
</script>

<template>
    <v-stage :config="stageSize">
        <v-layer>
            <!-- Room rectangle -->
            <v-rect :config="scaledBasedRect" />

            <!-- Desks -->
            <v-group
                v-for="desk in Object.values(desks)"
                :key="desk.id"
                :config="{ x: desk.x, y: desk.y, draggable: true }"
                @contextmenu="(event) => handleContextMenu(event, desk.id)"
                @dragend="(e) => handleDragEnd(e, desk.id)"
            >
                <!-- Desk -->
                <component
                    :is="desk.isOval ? 'v-ellipse' : 'v-circle'"
                    :config="
                        desk.isOval
                            ? {
                                  x: 0,
                                  y: 0,
                                  radiusX: desk.width / 2,
                                  radiusY: desk.height / 2,
                                  stroke: desk.stroke,
                                  fill: desk.fill,
                                  strokeWidth: desk.strokeWidth,
                              }
                            : {
                                  x: 0,
                                  y: 0,
                                  radius: desk.radius,
                                  stroke: desk.stroke,
                                  fill: desk.fill,
                                  strokeWidth: desk.strokeWidth,
                              }
                    "
                />
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
                        stroke: seat.stroke,
                        strokeWidth: seat.strokeWidth,
                    }"
                />
            </v-group>
        </v-layer>
    </v-stage>

    <ContextMenu
        :visible="showMenu"
        :position="menuPosition"
        :menu-options="[
            'placeOrder',
            'viewOrder',
            'generateCode',
            ...(isGroupDesk ? ['deleteGroup'] : []),
        ]"
        :desk-code="desks[selectedId]?.code || null"
        :desk-id="selectedId"
        :group-id="selectedGroupId"
        @placeOrder="handlePlaceOrder"
        @viewOrder="handleViewOrder"
        @generateCode="generateCode"
        @clearCode="clearCode"
        @deleteGroup="deleteGroup"
    />

    <!-- Collision Modal -->
    <div
        v-if="showCollisionModal"
        class="fixed top-0 left-0 w-full h-full flex items-center justify-center text-dark dark:text-white bg-black bg-opacity-50"
    >
        <div class="bg-white p-6 rounded shadow-lg">
            <h2 class="text-xl font-bold mb-4 text-black">
                {{ t("desksPage.mergeModal.title") }}
            </h2>
            <p class="text-black">
                {{ t("desksPage.mergeModal.selectTables") }}:
                {{ collisionPair[0] }} {{ collisionPair[1] }}.
            </p>
            <div class="flex justify-between">
                <AdminButton @click="mergeDesks" class="mt-4 px-4 py-2">
                    {{ t("desksPage.mergeModal.merge") }}
                </AdminButton>
                <DangerButton
                    @click="showCollisionModal = false"
                    class="mt-4 px-4 py-2"
                >
                    {{ t("desksPage.mergeModal.cancel") }}
                </DangerButton>
            </div>
        </div>
    </div>
</template>
