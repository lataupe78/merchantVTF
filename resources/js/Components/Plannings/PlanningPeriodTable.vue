<script setup>
import { Link } from "@inertiajs/vue3";
import { toFormat } from "@/helpers/dates.js";
const props = defineProps({
    totals: [Array, Object],
});
</script>
<template>
    <v-alert type="info" variant="tonal" class="my-8">
        Les horaires sont données en heures et cebtièmes d'heures
    </v-alert>

    <!-- <div class="alert alert-warning mb-5">
    <h2>TODO:</h2>
    determiner le calcul des jours à valider sur la période concernée pour l'employé ( 2023-09-24 : pas bon)
    <br>
    <strong>Il faut comparer les totaux des jours effectués ( started_at, ended_at non nuls ) et des jours validés
      (validated_started_at, validated_ended_at )</strong>
  </div> -->

    <v-card
        class="my-8"
        :title="
            trans_choice(
                'pagination_items_count',
                Object.keys(totals).length || 0,
                { item: __('worker'), items: __('workers') }
            )
        "
    >
    </v-card>

    <template v-for="(worker, workerName) in totals">
        <v-card :title="workerName">
            <v-card-text>
                <v-table fixed-header fixed-footer>
                    <thead>
                        <tr>
                            <th style="width: 12rem">{{ __("sale_point") }}</th>

                            <th>{{ __("total") }}</th>
                            <th>{{ __("total_reel") }}</th>
                            <th>{{ __("total_reel validated") }}</th>

                            <th class="text-info">
                                {{ __("completed_days_count") }}
                            </th>
                            <th class="text-primary">
                                {{ __("validated_completed_days_count") }}
                            </th>

                            <th class="text-info text-end">
                                {{ __("actions") }}
                            </th>

                            <!-- <th>{{__('max_ended_at')}}</th>
              <th>{{__('max_validated_at')}}</th>

              <th>{{__('days_to_validate')}}</th>
              <th>{{__('days_to_validate_from_now')}}</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="total in worker">
                            <td class="text-secondary fw-bold">
                                {{ total?.salepointName }}
                            </td>

                            <td class="border">
                                {{ Number(total?.result_prev).toFixed(2) }}
                            </td>
                            <td class="border">
                                {{ Number(total?.result_reel).toFixed(2) }}
                            </td>
                            <td class="border">
                                {{
                                    Number(
                                        total?.validated_result_reel
                                    ).toFixed(2)
                                }}
                            </td>

                            <td class="text-info my-3">
                                {{ total?.completed_days_count }}
                            </td>
                            <td class="text-primary my-3">
                                {{ total?.validated_completed_days_count }}
                            </td>

                            <td class="">
                                <!-- <span class="text-warning">
                  {{Number(total?.completed_days_count)-Number(total?.validated_completed_days_count)}} </span> -->

                                <span
                                    v-if="
                                        Number(total?.completed_days_count) -
                                            Number(
                                                total?.validated_completed_days_count
                                            ) >
                                        0
                                    "
                                    class="ms-3"
                                >
                                    <Link
                                        :href="
                                            route(
                                                'manager.plannings.validation.index'
                                            )
                                        "
                                        class="btn btn-outline-warning"
                                    >
                                        {{ __("schedule_validation") }}
                                    </Link>
                                </span>
                            </td>

                            <!-- <td class="text-warning my-3">
                {{toFormat(total?.max_ended_at, 'dd/MM/yyyy HH:mm:ss')}}
              </td>
              <td class="text-warning my-3">
                {{toFormat(total?.max_validated_at, 'dd/MM/yyyy HH:mm:ss')}}
              </td>

              <td>{{total?.days_to_validate}}</td>
               -->
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold">
                            <td class="text-uppercase">{{ __("totals") }}</td>
                            <td class="border text-info">
                                {{
                                    Number(
                                        worker.reduce(
                                            (total, workerTime) =>
                                                total +
                                                1 * workerTime.result_prev,
                                            0
                                        )
                                    ).toFixed(2)
                                }}
                            </td>
                            <td class="border text-info">
                                {{
                                    Number(
                                        worker.reduce(
                                            (total, workerTime) =>
                                                total +
                                                1 * workerTime.result_reel,
                                            0
                                        )
                                    ).toFixed(2)
                                }}
                            </td>
                            <td class="border text-info">
                                {{
                                    Number(
                                        worker.reduce(
                                            (total, workerTime) =>
                                                total +
                                                1 *
                                                    workerTime.validated_result_reel,
                                            0
                                        )
                                    ).toFixed(2)
                                }}
                            </td>
                        </tr>
                    </tfoot>
                </v-table>
            </v-card-text>
        </v-card>
    </template>
</template>
