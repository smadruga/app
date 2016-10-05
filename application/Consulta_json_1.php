<SciCumulus>
    <environment type="LOCAL"/>

    <constraint workflow_exectag="sciphy-1" cores="2" performance="false"/> 

    <workspace workflow_dir="/Users/Victor/Documents/Workflows/SciPhy"/>

    <database name="scc-sciphy" username="scc2" password="scc2" port="5432" server="localhost"/>

    <conceptualWorkflow tag="sciphy" description="Phylogeny using RAXML">

        <activity tag="mafft" type="MAP" template="%=WFDIR%/template_mafft" activation="./experiment.cmd" description="align with mafft">
            <relation reltype="Input" dependency="dataselection"/>
            <relation reltype="Output" name="omafft"/>
            <extractor name="dlmafft" type="LOADING" cartridge="EXTERNAL_PROGRAM" invocation="./extractor.cmd"/>
            <field name="NAME" type="text" output="omafft"/>
            <field name="FASTA_FILE" type="file" output="omafft" operation="COPY"/>
            <field name="MAFFT_FILE" type="file" output="omafft" operation="COPY" extractor="dlmafft"/>
            <field name="FASTA_NUMBERED" type="file" output="omafft" operation="COPY" extractor="dlmafft"/>
        </activity>
        
    </conceptualWorkflow>

    <executionWorkflow tag="modelagem" execmodel="PROTGAMMAWAG" expdir="%=WFDIR%/exp" adaptive="false">
        <relation name="IMod" filename="Input.dataset"/>
    </executionWorkflow>

</SciCumulus>