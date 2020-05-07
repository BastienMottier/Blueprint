package com.adraxas.squirclx

import com.github.javiersantos.piracychecker.PiracyChecker
import dev.jahir.blueprint.ui.activities.BottomNavigationBlueprintActivity

/**
 * You can choose between:
 * - DrawerBlueprintActivity
 * - BottomNavigationBlueprintActivity
 */
class MainActivity : BottomNavigationBlueprintActivity() {

    /**
     * These things here have the default values. You can delete the ones you don't want to change
     * and/or modify the ones you want to.
     */
    override val billingEnabled = true

    override fun amazonInstallsEnabled(): Boolean = true
    override fun checkLPF(): Boolean = false
    override fun checkStores(): Boolean = false
    override val isDebug: Boolean = BuildConfig.DEBUG

    /**
     * This is your app's license key. Get yours on Google Play Dev Console.
     * Default one isn't valid and could cause issues in your app.
     */
    override fun getLicKey(): String? = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAkazZYdZmu9nmN0OYcbhKWBWwHZlctLDPqJQoIR2QYV+s5ImUtVH4MY0ZlWcnk3OKuhJ09SR0tkdpwdkzBoPZATk6OlclRYd8xksrwqUrzPmIXOvza5wFVqPpwMHSYZPkwej40e9A4jCApvLbBlLZuBKuoq8yg9kQKrSHl2gdWHN+gu6JuU6TfqCXbhyDZJ30pUFiEhdDdBQreMLRlDz6xZpznAbfaCtiIAO1mJhNaBafPAtnSUQTtVlkyeQJuL/M42apPOwwL7NDjV/RjBMX5JLOduTvDaQ6ZxaxeSqTaVa6/LmRsevlxKfcFLpyXaE2bBeT2H9C0AryZ5epUbIOowIDAQAB"

    /**
     * This is the license checker code. Feel free to create your own implementation or
     * leave it as it is.
     * Anyways, keep the 'destroyChecker()' as the very first line of this code block
     * Return null to disable license check
     */
    override fun getLicenseChecker(): PiracyChecker? {
        destroyChecker() // Important
        return if (BuildConfig.DEBUG) null else super.getLicenseChecker()
    }

    override fun defaultTheme(): Int = R.style.MyApp_Default
    override fun amoledTheme(): Int = R.style.MyApp_Default_Amoled
}