<template>
    <FrontLayout title="About">
        <v-container>
            <div class="card">
                <div class="card-header">Roles & Permissions</div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item">
                            <h5 class="list-group-item-title">
                                <span class="badge bg-info">Super Admin</span>
                            </h5>
                            <p>Le Super Admin peut tout faire</p>
                            Voir Tous les Reports / Stocks / Assets de toutes
                            les Teams
                        </div>
                        <div class="list-group-item">
                            <h5 class="list-group-item-title">
                                <span class="badge bg-info">Seller</span>
                            </h5>
                            <p>
                                Un vendeur est attribué à un plusieurs points de
                                vente
                            </p>
                            Il peut :
                            <ul>
                                <li>publier des rapports journaliers</li>
                                <li>
                                    consulter ses rapports mensuels, annuels ...
                                </li>
                                <li>publier des rapports journaliers</li>
                                <li>
                                    consulter le rapport des points de vente
                                    auxquels il est associé
                                </li>
                                <li>
                                    Publier des rapports de stocks journaliers
                                    des points de vente auxquels il est associé
                                </li>
                            </ul>
                        </div>

                        <div class="list-group-item">
                            <h5 class="list-group-item-title">
                                <span class="badge bg-info">Manager</span>
                            </h5>
                            <p>Un manager gère un plusieurs points de vente</p>
                            <p>
                                Un manager gère les équipes associées à ces
                                points de vente
                            </p>
                            Il peut :
                            <ul>
                                <li>
                                    publier et modifier les rapports journaliers
                                </li>
                                <li>
                                    Il doit pouvoir trancher si plusieurs
                                    rapports ont été publiés le même jour pour
                                    un même point de vente
                                </li>
                                <li>
                                    modifier / révoquer les permissions des
                                    employés qu'il manage
                                </li>
                                <li>
                                    Publier, Consulter et modifier les données
                                    de stocks des points de vente
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </v-container>
    </FrontLayout>
</template>

<script setup>
import FrontLayout from "@/Layouts/FrontLayout.vue";
</script>
