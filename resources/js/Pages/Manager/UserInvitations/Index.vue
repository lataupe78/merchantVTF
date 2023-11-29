<script setup>
import { dateLabel, toFormat } from "@/helpers/dates.js";

import ManagerLayout from "@/Layouts/ManagerLayout.vue";
const props = defineProps({
    invitations: { type: [Array, Object], required: true },
    // filters: { type: [Array, Object], required: true },
});
</script>

<template>
    <ManagerLayout :title="__('invitations')">
       

        <PreDebug>{{ invitations }}</PreDebug>

        <v-container>
            <v-table>
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Created At</th>
                        <th>Invitation Link</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(invitation, index) in invitations.data"
                        :key="`invitation-${index}`"
                    >
                        <td>
                            <strong class="text-info">{{
                                invitation.email
                            }}</strong>
                        </td>
                        <td>
                            {{
                                toFormat(
                                    invitation?.created_at,
                                    "dd/MM/yyyy HH:mm:ss"
                                )
                            }}
                        </td>
                        <td>
                            <kbd>{{ invitation.link }}</kbd>
                        </td>
                    </tr>
                </tbody>
            </v-table>
        </v-container>
    </ManagerLayout>
</template>
