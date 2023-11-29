<script setup>
import { ref } from "vue";

defineEmits(["connect"]);

const props = defineProps({
    groups: {
        type: [Array, Object],
    },
});

const sections = ref([]);

sections.value = Array.from(Object.keys(props.groups)).map((group) => {
    return {
        value: group,
        count: props.groups?.[group]?.length ?? 0,
        label:
            group +
            ` <span class="badge bg-info">${
                props.groups?.[group]?.length || 0
            }</span>`,
    };
});

const tab = ref(null);
</script>

<template>
    <v-card variant="tonal" color="secondary">
        <v-card-title class="text-center">
            {{ __("Select an Example User") }}
        </v-card-title>
        <v-card-subtitle class="text-center">
            {{ __("Local Dev only") }}
        </v-card-subtitle>
        <v-card-text>
            <v-tabs
                v-model="tab"
                color="secondary"
                align-tabs="center"
                show-arrows
            >
                <v-tab
                    v-for="(section, index) in sections"
                    :key="index"
                    :value="section.value"
                >
                    <div class="d-flex text-body-1 text-uppercase">
                        {{ `${section.value} (${section.count})` }}
                    </div>
                </v-tab>
            </v-tabs>
        </v-card-text>
        <v-card-text>
            <v-window v-model="tab">
                <v-window-item
                    v-for="(section, role, index) in groups"
                    :value="role"
                >
                    <v-list>
                        <!-- <v-list-subheader class="text-uppercase">{{
                            role
                        }}</v-list-subheader> -->

                        <v-list-item
                            v-for="(user, userIndex) in section"
                            :key="`item-${role}-${userIndex}`"
                            :title="user?.name"
                            :value="user?.email"
                            density="comfortable"
                            ripple
                        >
                            <template v-slot:title>
                                <div class="text-body-1 font-weight-black text-capitalize">
                                    {{ user?.name || "User" }}
                                </div>
                            </template>

                            <div class="mb-8">
                                <p class="text-subtitle-1 text-muted mb-2">
                                    {{ user?.email }}
                                </p>
                                <!-- <v-list-item-subtitle> -->

                                <v-chip
                                    :color="
                                        !!user?.is_active ? 'success' : 'error'
                                    "
                                    >{{
                                        !!user?.is_active
                                            ? __("active")
                                            : __("inactive")
                                    }}
                                </v-chip>
                                <v-chip
                                    v-for="(role, roleIndex) in user.roles"
                                    :key="`user_${user.id}-role-${roleIndex}`"
                                    color="info"
                                >
                                    {{ role.name }}
                                </v-chip>
                            </div>

                            <!-- </v-list-item-subtitle> -->

                            <template v-slot:append>
                                <v-list-item-action>
                                    <v-btn
                                        color="primary"
                                        variant="outlined"
                                        icon="mdi-login"
                                        @click.stop="
                                            $emit('connect', { role, user })
                                        "
                                    />
                                </v-list-item-action>
                            </template>
                        </v-list-item>
                    </v-list>
                    <!-- <pre>{{ section }}</pre> -->
                </v-window-item>
            </v-window>
        </v-card-text>
    </v-card>
</template>

<style>
.list-group-users {
    max-height: 40vh;
    overflow-x: hidden;
    overflow-y: auto;
}

::-webkit-scrollbar {
    width: 8px;
    height: 8px;
    background-color: #464646;
    /* or add it to the track */
}

::-webkit-scrollbar-thumb {
    background: #868686;
}
</style>
