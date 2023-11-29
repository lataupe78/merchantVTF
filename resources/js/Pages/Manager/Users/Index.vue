<script setup>
import { ref, watch } from "vue";
import { router } from "@inertiajs/vue3";
import { toFormat } from "@/helpers/dates.js";

import ManagerLayout from "@/Layouts/ManagerLayout.vue";

import Sorter from "@/Components/Table/Sorter.vue";
import BoolLabel from "@/Components/BoolLabel.vue";
import MenuActions from "@/Components/Menus/Actions.vue";
import PaginationSelect from "@/Components/Table/PaginationSelect.vue";

import FiltersUser from "./_FiltersUser.vue";

const props = defineProps({
    users: { type: [Array, Object], required: true },
    filters: { type: [Array, Object], required: true },
    options: { type: [Array, Object], required: true },
});

const params = ref({ ...props.filters, ...{ sort_by: "", dir: "" } });

const sortParams = (field) => {
    console.log({ action: "SORT", field: field });
    params.value.sort_by = field;
    params.value.dir = params.value.dir === "asc" ? "desc" : "asc";
};

const updateCurrentFilters = (filters) => {
    console.log("updateCurrentFilters", { filters: filters });
    params.value = { ...params.value, ...filters };
};

watch(
    params,
    (newParams) => {
        console.log("watching params", newParams);

        router.get(route("manager.users.index"), newParams, {
            replace: true,
            preserveState: true,
            preserveScroll: true,
            onFinish: (visit) => {
                // processing.value = false;
            },
            onError(error) {
                console.error(error);
                // debugger;
            },
        });
    },
    {
        deep: true,
        immediate: false,
    }
);

const currentPage = ref(null);

const updatePagination = (page = null) => {
    params.value.page = page;
};

const deleteItem = (item) => {
    if (confirm("Are you sure ?") === true) {
        router.delete(route("manager.users.destroy", { user: item }), {
            onError(error) {
                console.error(error);
                // debugger;
            },
        });
    }
};

const labelCopyTextDefault = "Copy invitation link";
const labelCopyText = ref(labelCopyTextDefault);

const copyCode = async (invitationCode) => {
    try {
        await navigator.clipboard.writeText(invitationCode);
        labelCopyText.value = "copied";
    } catch (err) {
        console.error(err);
        labelCopyText.value = "error";
    }
};

const resetLabelCopyText = () => {
    labelCopyText.value = labelCopyTextDefault;
};

const sendInvitation = (user) => {
    alert("Sending invitation to " + user.name + "\r\nnot yet implemented");
};
</script>
<template>
    <ManagerLayout :title="__('users')">
        <template #actions>
            <div class="d-flex flex-wrap ga-4 me-4">
                <v-btn color="success" :to="route('manager.users.create')">
                    {{ __("create_item", { item: __("user") }) }}
                </v-btn>

                <v-btn color="primary" :to="route('manager.invitations.index')">
                    {{ __("invitations") }}</v-btn
                >
            </div>
        </template>

        <v-container>
            <FiltersUser
                :filters="filters"
                :options="options"
                @filters-updated="updateCurrentFilters"
            />

            <PaginationSelect sessionKey="pagination.users" />

            <v-table density="comfortable" fixed-header height="50vh">
                <thead>
                    <tr>
                        <th class="border" scope="col" rowspan="2">
                            <v-icon icon="mdi-dots-vertical" />
                        </th>
                        <th scope="col">
                            <Sorter
                                @sort="sortParams"
                                :params="params"
                                field="id"
                            />
                        </th>
                        <th scope="col">
                            <Sorter
                                @sort="sortParams"
                                :params="params"
                                field="name"
                            />
                        </th>
                        <th scope="col">
                            <Sorter
                                @sort="sortParams"
                                :params="params"
                                field="is_active"
                            />
                        </th>
                        <th scope="col">
                            {{ __("roles") }}
                        </th>
                        <th scope="col">
                            {{ __("sale_points") }}
                        </th>
                        <th scope="col">
                            {{ __("invitation") }}
                        </th>

                        <th scope="col">
                            <Sorter
                                @sort="sortParams"
                                :params="params"
                                field="created_at"
                            />
                            <Sorter
                                @sort="sortParams"
                                :params="params"
                                field="updated_at"
                            />
                        </th>
                        <th scope="col">
                            <Sorter
                                @sort="sortParams"
                                :params="params"
                                field="email_verified_at"
                            />
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="(user, index) in users?.data"
                        :key="index"
                        class=""
                    >
                        <!-- ACTIONS -->
                        <td>
                            <MenuActions
                                :items="[
                                    {
                                        label: __('edit'),
                                        url: route('manager.users.edit', {
                                            user: user,
                                        }),
                                        icon: 'mdi-pencil',
                                    },
                                ]"
                            >
                                <v-list-item
                                    :title="__('delete')"
                                    @click="deleteItem(user)"
                                >
                                    <template v-slot:prepend>
                                        <v-icon
                                            color="error"
                                            icon="mdi-delete"
                                        />
                                    </template>
                                </v-list-item>
                            </MenuActions>
                        </td>

                        <th scope="row">
                            <div class="text-muted">#{{ user.id }}</div>
                        </th>

                        <td style="max-width: 8rem">
                            <div
                                class="text-h6 text-wrap text-truncate text-capitalize mb-1"
                            >
                                {{ user?.name }}
                            </div>

                            <div
                                class="text-subtitle text-wrap text-truncate text-muted mb-1"
                            >
                                {{ user?.email }}
                            </div>
                        </td>

                        <td>
                            <BoolLabel
                                :value="!!user?.is_active"
                                :labels="[__('inactive'), __('active')]"
                            />
                        </td>
                        <!-- ROLES -->
                        <td>
                            <v-sheet
                                class="d-flex flex-wrap ga-4"
                                color="transparent"
                            >
                                <v-chip
                                    v-for="(role, index) in user?.roles"
                                    :key="`user-${user.id}-role-${index}`"
                                    :text="role?.name"
                                    color="info"
                                />
                            </v-sheet>
                        </td>
                        <!-- SALE_POINTS -->
                        <td>
                            <v-sheet
                                class="d-flex flex-wrap ga-4"
                                color="transparent"
                            >
                                <v-chip
                                    v-for="(
                                        sale_point, index
                                    ) in user?.sale_points"
                                    :key="`user-${user.id}-sale_point-${index}`"
                                    :text="sale_point?.name"
                                    color="cyan"
                                />
                            </v-sheet>
                        </td>
                        <!-- INVITATION -->
                        <td
                            class="text-wrap text-truncate text-muted"
                            style="max-width: 8rem"
                        >
                            <template v-if="user?.invitation">
                                <v-chip
                                    color="grey"
                                    text-color="white"
                                    class="font-weight-bold"
                                >
                                    <v-tooltip
                                        location="top"
                                        :text="labelCopyText"
                                    >
                                        <template v-slot:activator="{ props }">
                                            <v-icon
                                                :icon="
                                                    user?.invitation
                                                        ?.registered_at ?? false
                                                        ? 'mdi-email-check'
                                                        : 'mdi-email'
                                                "
                                                :color="
                                                    user?.invitation
                                                        ?.registered_at ?? false
                                                        ? 'green'
                                                        : 'gray'
                                                "
                                                v-bind="props"
                                                @mouseout="resetLabelCopyText"
                                                @click="
                                                    copyCode(
                                                        user.invitation.link
                                                    )
                                                "
                                            />
                                        </template>
                                    </v-tooltip>

                                    <template
                                        v-if="
                                            !!user?.invitation
                                                ?.registered_at === false
                                        "
                                    >
                                        <v-divider
                                            class="mx-4"
                                            vertical
                                            light
                                        />
                                        <v-icon
                                            color="primary"
                                            icon="mdi-send"
                                            @click="sendInvitation(user)"
                                        />
                                    </template>
                                </v-chip>

                                <p class="text-info text-wrap mt-2">
                                    {{
                                        __("sent_at", {
                                            at: toFormat(
                                                user.invitation.created_at,
                                                "dd/MM/yy HH:mm:ss"
                                            ),
                                        })
                                    }}
                                </p>
                                <p class="text-info text-wrap mt-2">
                                    {{
                                        __("registered_at", {
                                            at: toFormat(
                                                user.invitation.registered_at,
                                                "dd/MM/yy HH:mm:ss"
                                            ),
                                        })
                                    }}
                                </p>
                            </template>
                        </td>

                        <!-- DATES -->
                        <td class="text-info">
                            <p class="mb-2">
                                {{
                                    toFormat(
                                        user?.created_at,
                                        "dd/MM/yy HH:mm:ss"
                                    )
                                }}
                            </p>

                            <p class="mb-2">
                                {{
                                    toFormat(
                                        user?.updated_at,
                                        "dd/MM/yy HH:mm:ss"
                                    )
                                }}
                            </p>
                        </td>
                        <!-- EMAIL_VERIFIED_AT -->
                        <td class="text-info">
                            <p class="mb-2">
                                {{
                                    toFormat(
                                        user?.email_verified_at,
                                        "dd/MM/yy HH:mm:ss"
                                    )
                                }}
                            </p>
                        </td>
                    </tr>
                </tbody>
            </v-table>

            <v-pagination
                v-model="currentPage"
                :total-visible="8"
                :length="users?.meta?.last_page"
                @update:modelValue="updatePagination"
            ></v-pagination>
        </v-container>
    </ManagerLayout>
</template>
